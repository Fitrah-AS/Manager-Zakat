<?php

namespace App\Services;

use App\Enums\StatusPembayaranEnum;
use App\Models\JenisZakat;
use App\Models\MetodePembayaran;
use App\Models\Muzakki;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\PembayaranJiwa;
use App\Models\PerhitunganZakatMal;
use App\Models\Periode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PembayaranService
{
    public function __construct(
        private readonly ZakatCalculatorService $calculatorService,
        private readonly KasService $kasService,
        private readonly StokBerasService $stokBerasService,
        private readonly AuditLogService $auditLogService
    ) {}

    public function buatTransaksi(array $data, ?UploadedFile $buktiTransfer = null): Pembayaran
    {
        $periode = Periode::aktif()->with('tarifFitrah')->first();
        if (! $periode) {
            throw new RuntimeException('Tidak ada periode zakat aktif. Hubungi Admin untuk mengaktifkan periode.');
        }

        $jenisZakat = JenisZakat::findOrFail($data['jenis_zakat_id']);
        $metode = MetodePembayaran::findOrFail($data['metode_pembayaran_id']);

        $pathBukti = null;
        if ($buktiTransfer) {
            $ext = strtolower($buktiTransfer->getClientOriginalExtension() ?: 'jpg');
            $filename = 'bukti-' . now()->format('YmdHis') . '-' . Str::random(12) . '.' . $ext;
            $pathBukti = $buktiTransfer->storeAs('bukti-transfer', $filename, 'local');
        }

        $pembayaran = DB::transaction(function () use ($data, $periode, $jenisZakat, $metode, $pathBukti): Pembayaran {
            $muzakki = $this->resolveMuzakki($data);
            $kodeTransaksi = $this->generateKodeTransaksi();

            $bentukBayar = 'uang';
            $totalUang = 0.0;
            $totalBerasKg = 0.0;
            $jumlahJiwa = null;
            $anggotaList = [];
            $malSummary = null;

            if ($jenisZakat->kategori === 'fitrah') {
                $bentukBayar = ($data['bentuk_bayar'] ?? 'uang') === 'beras' ? 'beras' : 'uang';
                $anggotaList = array_values(array_filter(
                    $data['anggota_jiwa'] ?? [],
                    fn ($row) => ! empty(trim((string) ($row['nama_anggota'] ?? '')))
                ));

                if (count($anggotaList) === 0) {
                    $anggotaList = [['nama_anggota' => $muzakki->nama_lengkap, 'hubungan_keluarga' => 'Kepala Keluarga']];
                }

                $jumlahJiwa = count($anggotaList);
                $hasilFitrah = $this->calculatorService->hitungFitrah($periode, $bentukBayar, $jumlahJiwa);
                $totalUang = $hasilFitrah['nominal_uang'];
                $totalBerasKg = $hasilFitrah['jumlah_beras_kg'];
            } else {
                // Zakat Mal (uang, atau beras khusus hasil pertanian)
                $bentukBayar = ($data['bentuk_bayar'] ?? 'uang') === 'beras' ? 'beras' : 'uang';
                $nominalManual = (float) ($data['nominal_uang'] ?? 0);
                $berasManual = (float) ($data['jumlah_beras_kg'] ?? 0);

                if (! empty($data['gunakan_kalkulator_mal'])) {
                    $malSummary = $this->calculatorService->hitungMal($data, $periode);
                    if ($bentukBayar === 'beras') {
                        $totalBerasKg = $berasManual > 0 ? $berasManual : (float) ($malSummary['hasil_zakat_kg'] ?? 0);
                    } else {
                        $totalUang = $nominalManual > 0 ? $nominalManual : (float) ($malSummary['hasil_zakat_uang'] ?? $malSummary['hasil_zakat']);
                    }
                } else {
                    if ($bentukBayar === 'beras') {
                        $totalBerasKg = $berasManual;
                    } else {
                        $totalUang = $nominalManual;
                    }
                }

                if ($bentukBayar === 'uang' && $totalUang <= 0) {
                    throw new RuntimeException('Nominal zakat mal (uang) harus lebih besar dari Rp 0.');
                }

                if ($bentukBayar === 'beras' && $totalBerasKg <= 0) {
                    throw new RuntimeException('Jumlah beras zakat mal (kg) harus lebih besar dari 0 kg.');
                }
            }

            // Pembayaran tunai langsung diverifikasi oleh petugas sesuai SOP lembaga
            $isTunai = $metode->isTunai();
            $status = $isTunai
                ? StatusPembayaranEnum::TERVERIFIKASI->value
                : StatusPembayaranEnum::MENUNGGU_VERIFIKASI->value;

            $actorId = Auth::id();

            $pembayaran = Pembayaran::create([
                'kode_transaksi' => $kodeTransaksi,
                'periode_id' => $periode->id,
                'muzakki_id' => $muzakki->id,
                'metode_pembayaran_id' => $metode->id,
                'petugas_input_id' => $actorId,
                'tanggal_bayar' => $data['tanggal_bayar'] ?? now(),
                'total_uang' => $totalUang,
                'total_beras_kg' => $totalBerasKg,
                'bukti_transfer' => $pathBukti,
                'status' => $status,
                'verified_by' => $isTunai ? $actorId : null,
                'verified_at' => $isTunai ? now() : null,
                'catatan' => $data['catatan'] ?? null,
            ]);

            $detail = PembayaranDetail::create([
                'pembayaran_id' => $pembayaran->id,
                'jenis_zakat_id' => $jenisZakat->id,
                'bentuk_bayar' => $bentukBayar,
                'jumlah_jiwa' => $jumlahJiwa,
                'nominal_uang' => $totalUang,
                'jumlah_beras_kg' => $totalBerasKg,
                'keterangan' => $data['catatan'] ?? null,
            ]);

            foreach ($anggotaList as $jiwa) {
                PembayaranJiwa::create([
                    'pembayaran_detail_id' => $detail->id,
                    'nama_anggota' => trim((string) $jiwa['nama_anggota']),
                    'hubungan_keluarga' => ! empty($jiwa['hubungan_keluarga']) ? trim((string) $jiwa['hubungan_keluarga']) : null,
                ]);
            }

            if ($malSummary) {
                PerhitunganZakatMal::create([
                    'pembayaran_detail_id' => $detail->id,
                    'nisab_id' => $malSummary['nisab_id'],
                    'jenis_harta' => $malSummary['jenis_harta'],
                    'metode_hitung' => $malSummary['metode_hitung'],
                    'nilai_harta_kotor' => $malSummary['nilai_harta_kotor'],
                    'hutang_jatuh_tempo' => $malSummary['hutang_jatuh_tempo'],
                    'harta_bersih' => $malSummary['harta_bersih'],
                    'nilai_nisab_saat_hitung' => $malSummary['nilai_nisab_saat_hitung'],
                    'sudah_haul' => $malSummary['sudah_haul'],
                    'memenuhi_nisab' => $malSummary['memenuhi_nisab'],
                    'persentase_zakat' => $malSummary['persentase_zakat'],
                    'hasil_zakat' => $totalUang,
                    'catatan_perhitungan' => $malSummary['catatan_perhitungan'],
                ]);
            }

            // Jika tunai (langsung terverifikasi), langsung catat ke Buku Kas Umum atau Stok Beras
            if ($isTunai) {
                if ($totalUang > 0) {
                    $this->kasService->catatMasuk(
                        periode: $periode,
                        nominal: $totalUang,
                        kategori: 'penerimaan_zakat',
                        keterangan: "Penerimaan tunai {$jenisZakat->nama_jenis} ({$kodeTransaksi}) dari {$muzakki->nama_lengkap}",
                        referensi: $pembayaran,
                        userId: $actorId
                    );
                }

                if ($totalBerasKg > 0) {
                    $this->stokBerasService->catatMasukDariPembayaran($pembayaran, $actorId);
                }
            }

            return $pembayaran;
        });

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'pembayaran',
            deskripsi: "Mencatat transaksi pembayaran {$pembayaran->kode_transaksi} dengan status {$pembayaran->status->value}.",
            model: $pembayaran,
            dataBaru: $pembayaran->toArray()
        );

        return $pembayaran;
    }

    private function resolveMuzakki(array $data): Muzakki
    {
        if (($data['mode_muzakki'] ?? 'pilih') === 'baru') {
            return Muzakki::create([
                'nama_lengkap' => $data['muzakki_baru_nama'],
                'nik' => $data['muzakki_baru_nik'] ?: null,
                'no_hp' => $data['muzakki_baru_no_hp'],
                'email' => $data['muzakki_baru_email'] ?: null,
                'alamat' => $data['muzakki_baru_alamat'],
            ]);
        }

        return Muzakki::findOrFail($data['muzakki_id']);
    }

    private function generateKodeTransaksi(): string
    {
        $prefix = 'ZKT-' . now()->format('Ymd') . '-';

        $lastKode = Pembayaran::where('kode_transaksi', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('kode_transaksi')
            ->value('kode_transaksi');

        $nextNumber = 1;
        if ($lastKode) {
            $lastSeq = (int) substr($lastKode, -4);
            $nextNumber = $lastSeq + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
