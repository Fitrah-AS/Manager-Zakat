<?php

namespace App\Services;

use App\Enums\StatusPembayaranEnum;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class VerifikasiPembayaranService
{
    public function __construct(
        private readonly KasService $kasService,
        private readonly StokBerasService $stokBerasService,
        private readonly AuditLogService $auditLogService
    ) {}

    public function terima(Pembayaran $pembayaran, User $verifikator): Pembayaran
    {
        $updated = DB::transaction(function () use ($pembayaran, $verifikator): Pembayaran {
            $locked = Pembayaran::with(['periode', 'muzakki', 'details.jenisZakat'])
                ->lockForUpdate()
                ->findOrFail($pembayaran->id);

            if ($locked->status !== StatusPembayaranEnum::MENUNGGU_VERIFIKASI) {
                throw new RuntimeException('Transaksi ini sudah diproses sebelumnya dan tidak berada dalam status menunggu verifikasi.');
            }

            $locked->update([
                'status' => StatusPembayaranEnum::TERVERIFIKASI->value,
                'verified_by' => $verifikator->id,
                'verified_at' => now(),
                'alasan_penolakan' => null,
            ]);

            $jenisNama = $locked->details->pluck('jenisZakat.nama_jenis')->filter()->implode(', ');

            if ((float) $locked->total_uang > 0) {
                $this->kasService->catatMasuk(
                    periode: $locked->periode,
                    nominal: (float) $locked->total_uang,
                    kategori: 'penerimaan_zakat',
                    keterangan: "Verifikasi {$jenisNama} ({$locked->kode_transaksi}) dari {$locked->muzakki->nama_lengkap}",
                    referensi: $locked,
                    userId: $verifikator->id
                );
            }

            if ((float) $locked->total_beras_kg > 0) {
                $this->stokBerasService->catatMasukDariPembayaran($locked, $verifikator->id);
            }

            return $locked->fresh();
        });

        $this->auditLogService->record(
            aksi: 'verify_approve',
            modul: 'verifikasi_pembayaran',
            deskripsi: "Menerima verifikasi pembayaran {$updated->kode_transaksi} oleh {$verifikator->name}.",
            model: $updated,
            dataBaru: ['status' => $updated->status->value, 'verified_by' => $verifikator->id]
        );

        return $updated;
    }

    public function tolak(Pembayaran $pembayaran, User $verifikator, string $alasan): Pembayaran
    {
        $updated = DB::transaction(function () use ($pembayaran, $verifikator, $alasan): Pembayaran {
            $locked = Pembayaran::lockForUpdate()->findOrFail($pembayaran->id);

            if ($locked->status !== StatusPembayaranEnum::MENUNGGU_VERIFIKASI) {
                throw new RuntimeException('Transaksi ini sudah diproses sebelumnya.');
            }

            $locked->update([
                'status' => StatusPembayaranEnum::DITOLAK->value,
                'verified_by' => $verifikator->id,
                'verified_at' => now(),
                'alasan_penolakan' => trim($alasan),
            ]);

            return $locked->fresh();
        });

        $this->auditLogService->record(
            aksi: 'verify_reject',
            modul: 'verifikasi_pembayaran',
            deskripsi: "Menolak pembayaran {$updated->kode_transaksi} oleh {$verifikator->name}. Alasan: {$alasan}",
            model: $updated,
            dataBaru: ['status' => $updated->status->value, 'alasan_penolakan' => $alasan]
        );

        return $updated;
    }
}
