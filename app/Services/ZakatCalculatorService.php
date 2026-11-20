<?php

namespace App\Services;

use App\Models\Nisab;
use App\Models\Periode;
use InvalidArgumentException;

class ZakatCalculatorService
{
    public function hitungFitrah(Periode $periode, string $bentukBayar, int $jumlahJiwa): array
    {
        if ($jumlahJiwa < 1) {
            throw new InvalidArgumentException('Jumlah jiwa minimal 1 orang.');
        }

        $tarif = $periode->tarifFitrah;
        if (! $tarif) {
            throw new InvalidArgumentException('Tarif zakat fitrah untuk periode aktif belum diatur.');
        }

        if ($bentukBayar === 'beras') {
            $jumlahBeras = round((float) $tarif->beras_per_jiwa_kg * $jumlahJiwa, 2);

            return [
                'bentuk_bayar' => 'beras',
                'jumlah_jiwa' => $jumlahJiwa,
                'nominal_uang' => 0.0,
                'jumlah_beras_kg' => $jumlahBeras,
                'tarif_satuan' => (float) $tarif->beras_per_jiwa_kg,
            ];
        }

        $nominalUang = round((float) $tarif->nominal_uang * $jumlahJiwa, 2);

        return [
            'bentuk_bayar' => 'uang',
            'jumlah_jiwa' => $jumlahJiwa,
            'nominal_uang' => $nominalUang,
            'jumlah_beras_kg' => 0.0,
            'tarif_satuan' => (float) $tarif->nominal_uang,
        ];
    }

    /**
     * Menghitung zakat mal berdasarkan jenis harta dan ketentuan nisab/haul yang berlaku.
     * Catatan: Hasil perhitungan merupakan alat bantu hitung administratif, bukan fatwa agama.
     */
    public function hitungMal(array $input, ?Periode $periode = null): array
    {
        $jenisHarta = (string) ($input['jenis_harta'] ?? 'emas_perak');
        $nisab = Nisab::query()
            ->where('jenis_harta', $jenisHarta)
            ->where('is_active', true)
            ->when($periode, fn ($q) => $q->where(fn ($sub) => $sub->where('periode_id', $periode->id)->orWhereNull('periode_id')))
            ->orderByDesc('periode_id')
            ->first();

        $hargaEmas = (float) ($input['harga_emas_per_gram'] ?? $nisab?->harga_emas_per_gram ?? 1500000);
        if ($hargaEmas <= 0) {
            $hargaEmas = 1500000;
        }

        return match ($jenisHarta) {
            'emas_perak' => $this->hitungEmasPerak($input, $nisab, $hargaEmas),
            'penghasilan' => $this->hitungPenghasilan($input, $nisab, $hargaEmas),
            'perdagangan' => $this->hitungPerdagangan($input, $nisab, $hargaEmas),
            'tabungan_investasi' => $this->hitungTabungan($input, $nisab, $hargaEmas),
            'pertanian' => $this->hitungPertanian($input, $nisab),
            default => throw new InvalidArgumentException('Jenis harta zakat mal tidak dikenali.'),
        };
    }

    private function hitungEmasPerak(array $input, ?Nisab $nisab, float $hargaEmas): array
    {
        $hartaKotor = max(0, (float) ($input['nilai_harta_kotor'] ?? 0));
        $hutang = max(0, (float) ($input['hutang_jatuh_tempo'] ?? 0));
        $hartaBersih = max(0, $hartaKotor - $hutang);
        $nilaiNisab = $nisab && $nisab->nilai_nisab_uang > 0 ? (float) $nisab->nilai_nisab_uang : ($hargaEmas * 85);
        $sudahHaul = filter_var($input['sudah_haul'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $persentase = (float) ($nisab?->kadar_zakat_persen ?? 2.5);

        $memenuhiNisab = $hartaBersih >= $nilaiNisab;
        $wajibZakat = $memenuhiNisab && $sudahHaul;
        $hasilZakat = $wajibZakat ? round($hartaBersih * ($persentase / 100), 2) : 0.0;

        return [
            'nisab_id' => $nisab?->id,
            'jenis_harta' => 'emas_perak',
            'metode_hitung' => 'haul_85_gram_emas',
            'nilai_harta_kotor' => $hartaKotor,
            'hutang_jatuh_tempo' => $hutang,
            'harta_bersih' => $hartaBersih,
            'nilai_nisab_saat_hitung' => $nilaiNisab,
            'sudah_haul' => $sudahHaul,
            'memenuhi_nisab' => $memenuhiNisab,
            'wajib_zakat' => $wajibZakat,
            'persentase_zakat' => $persentase,
            'hasil_zakat' => $hasilZakat,
            'satuan_hasil' => 'uang',
            'catatan_perhitungan' => 'Emas & Perak: (Nilai Harta - Hutang Jatuh Tempo) x 2,5% jika mencapai nisab 85 gr emas dan haul 1 tahun.',
        ];
    }

    private function hitungPenghasilan(array $input, ?Nisab $nisab, float $hargaEmas): array
    {
        $metodeHitung = ($input['metode_penghasilan'] ?? 'neto') === 'bruto' ? 'bruto' : 'neto';
        $periodePenghasilan = ($input['periode_penghasilan'] ?? 'bulanan') === 'tahunan' ? 'tahunan' : 'bulanan';

        $penghasilan = max(0, (float) ($input['nilai_harta_kotor'] ?? 0));
        $pengurang = $metodeHitung === 'neto' ? max(0, (float) ($input['hutang_jatuh_tempo'] ?? 0)) : 0.0;
        $penghasilanBersih = max(0, $penghasilan - $pengurang);

        $nisabTahunan = $nisab && $nisab->nilai_nisab_uang > 0 ? (float) $nisab->nilai_nisab_uang : ($hargaEmas * 85);
        $nilaiNisab = $periodePenghasilan === 'bulanan' ? round($nisabTahunan / 12, 2) : $nisabTahunan;
        $persentase = (float) ($nisab?->kadar_zakat_persen ?? 2.5);

        $memenuhiNisab = $penghasilanBersih >= $nilaiNisab;
        $hasilZakat = $memenuhiNisab ? round($penghasilanBersih * ($persentase / 100), 2) : 0.0;

        return [
            'nisab_id' => $nisab?->id,
            'jenis_harta' => 'penghasilan',
            'metode_hitung' => "{$metodeHitung}_{$periodePenghasilan}",
            'nilai_harta_kotor' => $penghasilan,
            'hutang_jatuh_tempo' => $pengurang,
            'harta_bersih' => $penghasilanBersih,
            'nilai_nisab_saat_hitung' => $nilaiNisab,
            'sudah_haul' => true,
            'memenuhi_nisab' => $memenuhiNisab,
            'wajib_zakat' => $memenuhiNisab,
            'persentase_zakat' => $persentase,
            'hasil_zakat' => $hasilZakat,
            'satuan_hasil' => 'uang',
            'catatan_perhitungan' => "Zakat Penghasilan ({$metodeHitung}, {$periodePenghasilan}): Nisab setara 85 gr emas/tahun" . ($periodePenghasilan === 'bulanan' ? ' dibagi 12 bulan.' : '.'),
        ];
    }

    private function hitungPerdagangan(array $input, ?Nisab $nisab, float $hargaEmas): array
    {
        $asetLancar = max(0, (float) ($input['nilai_harta_kotor'] ?? 0));
        $hutangPendek = max(0, (float) ($input['hutang_jatuh_tempo'] ?? 0));
        $hartaBersih = max(0, $asetLancar - $hutangPendek);
        $nilaiNisab = $nisab && $nisab->nilai_nisab_uang > 0 ? (float) $nisab->nilai_nisab_uang : ($hargaEmas * 85);
        $sudahHaul = filter_var($input['sudah_haul'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $persentase = (float) ($nisab?->kadar_zakat_persen ?? 2.5);

        $memenuhiNisab = $hartaBersih >= $nilaiNisab;
        $wajibZakat = $memenuhiNisab && $sudahHaul;
        $hasilZakat = $wajibZakat ? round($hartaBersih * ($persentase / 100), 2) : 0.0;

        return [
            'nisab_id' => $nisab?->id,
            'jenis_harta' => 'perdagangan',
            'metode_hitung' => 'aset_dagang_bersih',
            'nilai_harta_kotor' => $asetLancar,
            'hutang_jatuh_tempo' => $hutangPendek,
            'harta_bersih' => $hartaBersih,
            'nilai_nisab_saat_hitung' => $nilaiNisab,
            'sudah_haul' => $sudahHaul,
            'memenuhi_nisab' => $memenuhiNisab,
            'wajib_zakat' => $wajibZakat,
            'persentase_zakat' => $persentase,
            'hasil_zakat' => $hasilZakat,
            'satuan_hasil' => 'uang',
            'catatan_perhitungan' => 'Zakat Perdagangan: (Modal Diputar + Stok Barang + Piutang Lancar - Hutang Jangka Pendek) x 2,5% dengan haul 1 tahun.',
        ];
    }

    private function hitungTabungan(array $input, ?Nisab $nisab, float $hargaEmas): array
    {
        $saldo = max(0, (float) ($input['nilai_harta_kotor'] ?? 0));
        $hutang = max(0, (float) ($input['hutang_jatuh_tempo'] ?? 0));
        $hartaBersih = max(0, $saldo - $hutang);
        $nilaiNisab = $nisab && $nisab->nilai_nisab_uang > 0 ? (float) $nisab->nilai_nisab_uang : ($hargaEmas * 85);
        $sudahHaul = filter_var($input['sudah_haul'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $persentase = (float) ($nisab?->kadar_zakat_persen ?? 2.5);

        $memenuhiNisab = $hartaBersih >= $nilaiNisab;
        $wajibZakat = $memenuhiNisab && $sudahHaul;
        $hasilZakat = $wajibZakat ? round($hartaBersih * ($persentase / 100), 2) : 0.0;

        return [
            'nisab_id' => $nisab?->id,
            'jenis_harta' => 'tabungan_investasi',
            'metode_hitung' => 'saldo_haul_85_gram',
            'nilai_harta_kotor' => $saldo,
            'hutang_jatuh_tempo' => $hutang,
            'harta_bersih' => $hartaBersih,
            'nilai_nisab_saat_hitung' => $nilaiNisab,
            'sudah_haul' => $sudahHaul,
            'memenuhi_nisab' => $memenuhiNisab,
            'wajib_zakat' => $wajibZakat,
            'persentase_zakat' => $persentase,
            'hasil_zakat' => $hasilZakat,
            'satuan_hasil' => 'uang',
            'catatan_perhitungan' => 'Tabungan & Investasi: (Saldo Simpanan/Investasi - Hutang Jatuh Tempo) x 2,5% jika mencapai nisab 85 gr emas dan haul 1 tahun.',
        ];
    }

    private function hitungPertanian(array $input, ?Nisab $nisab): array
    {
        $jenisPanen = ($input['jenis_panen'] ?? 'gabah') === 'beras' ? 'beras' : 'gabah';
        $nisabKg = $jenisPanen === 'beras' ? 520.0 : (float) ($nisab?->nisab_pertanian_kg ?? 653.0);

        $beratPanenKg = max(0, (float) ($input['berat_panen_kg'] ?? $input['nilai_harta_kotor'] ?? 0));
        $biayaPanen = max(0, (float) ($input['hutang_jatuh_tempo'] ?? 0));
        $hargaPerKg = max(0, (float) ($input['harga_per_kg'] ?? 0));
        $jenisIrigasi = ($input['jenis_irigasi'] ?? 'berbiaya') === 'alami' ? 'alami' : 'berbiaya';
        $persentase = $jenisIrigasi === 'alami' ? 10.0 : 5.0;

        $memenuhiNisab = $beratPanenKg >= $nisabKg;
        $nilaiPanenUang = $hargaPerKg > 0 ? round($beratPanenKg * $hargaPerKg, 2) : 0.0;
        $nilaiBersih = max(0, $nilaiPanenUang - $biayaPanen);

        $hasilZakatKg = $memenuhiNisab ? round($beratPanenKg * ($persentase / 100), 2) : 0.0;
        $hasilZakatUang = ($memenuhiNisab && $hargaPerKg > 0)
            ? round(($nilaiBersih > 0 ? $nilaiBersih : $nilaiPanenUang) * ($persentase / 100), 2)
            : 0.0;

        return [
            'nisab_id' => $nisab?->id,
            'jenis_harta' => 'pertanian',
            'metode_hitung' => "panen_{$jenisPanen}_{$jenisIrigasi}",
            'nilai_harta_kotor' => $nilaiPanenUang > 0 ? $nilaiPanenUang : $beratPanenKg,
            'hutang_jatuh_tempo' => $biayaPanen,
            'harta_bersih' => $nilaiPanenUang > 0 ? $nilaiBersih : $beratPanenKg,
            'nilai_nisab_saat_hitung' => $nisabKg,
            'sudah_haul' => true,
            'memenuhi_nisab' => $memenuhiNisab,
            'wajib_zakat' => $memenuhiNisab,
            'persentase_zakat' => $persentase,
            'hasil_zakat' => $hasilZakatUang > 0 ? $hasilZakatUang : $hasilZakatKg,
            'hasil_zakat_uang' => $hasilZakatUang,
            'hasil_zakat_kg' => $hasilZakatKg,
            'berat_panen_kg' => $beratPanenKg,
            'satuan_hasil' => $hasilZakatUang > 0 ? 'uang' : 'kg',
            'catatan_perhitungan' => "Zakat Pertanian ({$jenisPanen}, irigasi {$jenisIrigasi}): Kadar {$persentase}% saat panen jika mencapai nisab {$nisabKg} kg (tanpa haul).",
        ];
    }
}
