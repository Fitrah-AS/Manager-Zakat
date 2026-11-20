<?php

namespace App\Services;

use App\Models\KasTransaksi;
use App\Models\Periode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class KasService
{
    public function getSaldoSaatIni(): float
    {
        return (float) (KasTransaksi::lockForUpdate()->latest('id')->value('saldo_setelah') ?? 0);
    }

    public function getSaldoReadOnly(): float
    {
        return (float) (KasTransaksi::latest('id')->value('saldo_setelah') ?? 0);
    }

    public function catatMasuk(
        Periode $periode,
        float $nominal,
        string $kategori,
        string $keterangan,
        ?Model $referensi = null,
        ?int $userId = null
    ): KasTransaksi {
        if ($nominal <= 0) {
            throw new RuntimeException('Nominal kas masuk harus lebih besar dari 0.');
        }

        $saldoSebelum = $this->getSaldoSaatIni();
        $saldoSetelah = round($saldoSebelum + $nominal, 2);

        return KasTransaksi::create([
            'periode_id' => $periode->id,
            'tanggal_transaksi' => now(),
            'jenis_arus' => 'masuk',
            'kategori' => $kategori,
            'nominal' => $nominal,
            'saldo_setelah' => $saldoSetelah,
            'referensi_type' => $referensi ? $referensi::class : null,
            'referensi_id' => $referensi?->getKey(),
            'keterangan' => $keterangan,
            'created_by' => $userId ?? Auth::id(),
        ]);
    }

    public function catatKeluar(
        Periode $periode,
        float $nominal,
        string $kategori,
        string $keterangan,
        ?Model $referensi = null,
        ?int $userId = null
    ): KasTransaksi {
        if ($nominal <= 0) {
            throw new RuntimeException('Nominal kas keluar harus lebih besar dari 0.');
        }

        $saldoSebelum = $this->getSaldoSaatIni();
        if ($nominal > $saldoSebelum) {
            $tersedia = number_format($saldoSebelum, 0, ',', '.');
            throw new RuntimeException("Saldo kas uang tidak mencukupi. Saldo kas tersedia saat ini: Rp {$tersedia}.");
        }

        $saldoSetelah = round($saldoSebelum - $nominal, 2);

        return KasTransaksi::create([
            'periode_id' => $periode->id,
            'tanggal_transaksi' => now(),
            'jenis_arus' => 'keluar',
            'kategori' => $kategori,
            'nominal' => $nominal,
            'saldo_setelah' => $saldoSetelah,
            'referensi_type' => $referensi ? $referensi::class : null,
            'referensi_id' => $referensi?->getKey(),
            'keterangan' => $keterangan,
            'created_by' => $userId ?? Auth::id(),
        ]);
    }
}
