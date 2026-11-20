<?php

namespace App\Services;

use App\Models\MasukStokBeras;
use App\Models\MutasiStokBeras;
use App\Models\Pembayaran;
use App\Models\Periode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class StokBerasService
{
    public function getStokSaatIniKg(): float
    {
        return (float) (MutasiStokBeras::lockForUpdate()->latest('id')->value('saldo_sesudah_kg') ?? 0);
    }

    public function getStokReadOnlyKg(): float
    {
        return (float) (MutasiStokBeras::latest('id')->value('saldo_sesudah_kg') ?? 0);
    }

    public function catatMasukDariPembayaran(Pembayaran $pembayaran, ?int $userId = null): ?MasukStokBeras
    {
        $jumlahKg = (float) $pembayaran->total_beras_kg;
        if ($jumlahKg <= 0) {
            return null;
        }

        if ($pembayaran->masukStokBeras()->exists()) {
            return $pembayaran->masukStokBeras;
        }

        $actorId = $userId ?? Auth::id();
        $hasMal = $pembayaran->details()->whereHas('jenisZakat', fn ($q) => $q->where('kategori', 'mal'))->exists();
        $sumber = $hasMal ? 'zakat_mal' : 'zakat_fitrah';

        $masuk = MasukStokBeras::create([
            'periode_id' => $pembayaran->periode_id,
            'pembayaran_id' => $pembayaran->id,
            'tanggal_masuk' => $pembayaran->tanggal_bayar?->toDateString() ?? now()->toDateString(),
            'jumlah_kg' => $jumlahKg,
            'sumber' => $sumber,
            'keterangan' => "Penerimaan beras terverifikasi dari transaksi {$pembayaran->kode_transaksi}",
            'created_by' => $actorId,
        ]);

        $this->catatMutasi(
            periodeId: $pembayaran->periode_id,
            tipe: 'masuk',
            jumlahKg: $jumlahKg,
            keterangan: "Masuk dari pembayaran zakat {$pembayaran->kode_transaksi}",
            referensi: $pembayaran,
            userId: $actorId
        );

        return $masuk;
    }

    public function catatMasukManual(
        Periode $periode,
        float $jumlahKg,
        string $sumber,
        string $tanggalMasuk,
        ?string $keterangan = null,
        ?int $userId = null
    ): MasukStokBeras {
        if ($jumlahKg <= 0) {
            throw new RuntimeException('Jumlah beras masuk harus lebih besar dari 0 kg.');
        }

        $actorId = $userId ?? Auth::id();

        $masuk = MasukStokBeras::create([
            'periode_id' => $periode->id,
            'pembayaran_id' => null,
            'tanggal_masuk' => $tanggalMasuk,
            'jumlah_kg' => $jumlahKg,
            'sumber' => $sumber,
            'keterangan' => $keterangan,
            'created_by' => $actorId,
        ]);

        $this->catatMutasi(
            periodeId: $periode->id,
            tipe: 'masuk',
            jumlahKg: $jumlahKg,
            keterangan: $keterangan ?: "Pemasukan beras manual ({$sumber})",
            referensi: $masuk,
            userId: $actorId
        );

        return $masuk;
    }

    public function catatKeluar(
        int $periodeId,
        float $jumlahKg,
        string $keterangan,
        ?Model $referensi = null,
        ?int $userId = null
    ): MutasiStokBeras {
        if ($jumlahKg <= 0) {
            throw new RuntimeException('Jumlah beras keluar harus lebih besar dari 0 kg.');
        }

        $saldoSebelum = $this->getStokSaatIniKg();
        if ($jumlahKg > $saldoSebelum) {
            $tersedia = number_format($saldoSebelum, 2, ',', '.');
            throw new RuntimeException("Stok beras tidak mencukupi. Stok tersedia saat ini: {$tersedia} kg.");
        }

        return $this->catatMutasi(
            periodeId: $periodeId,
            tipe: 'keluar',
            jumlahKg: $jumlahKg,
            keterangan: $keterangan,
            referensi: $referensi,
            userId: $userId
        );
    }

    private function catatMutasi(
        int $periodeId,
        string $tipe,
        float $jumlahKg,
        string $keterangan,
        ?Model $referensi = null,
        ?int $userId = null
    ): MutasiStokBeras {
        $saldoSebelum = $this->getStokSaatIniKg();
        $saldoSesudah = $tipe === 'keluar'
            ? round($saldoSebelum - $jumlahKg, 2)
            : round($saldoSebelum + $jumlahKg, 2);

        return MutasiStokBeras::create([
            'periode_id' => $periodeId,
            'tanggal_mutasi' => now(),
            'tipe' => $tipe,
            'jumlah_kg' => $jumlahKg,
            'saldo_sebelum_kg' => $saldoSebelum,
            'saldo_sesudah_kg' => $saldoSesudah,
            'referensi_type' => $referensi ? $referensi::class : null,
            'referensi_id' => $referensi?->getKey(),
            'keterangan' => $keterangan,
            'created_by' => $userId ?? Auth::id(),
        ]);
    }
}
