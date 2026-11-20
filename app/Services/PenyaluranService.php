<?php

namespace App\Services;

use App\Enums\BentukZakatEnum;
use App\Enums\StatusKelayakanEnum;
use App\Enums\StatusPenyaluranEnum;
use App\Models\Mustahik;
use App\Models\Penyaluran;
use App\Models\PenyaluranDetail;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PenyaluranService
{
    public function __construct(
        private readonly KasService $kasService,
        private readonly StokBerasService $stokBerasService,
        private readonly AuditLogService $auditLogService
    ) {}

    public function buatPenyaluran(array $data, User $petugas, bool $langsungSelesai = false): Penyaluran
    {
        $periode = Periode::aktif()->first();
        if (! $periode) {
            throw new RuntimeException('Tidak ada periode zakat aktif. Hubungi Admin.');
        }

        $items = $data['items'] ?? [];
        if (count($items) === 0) {
            throw new RuntimeException('Minimal harus ada 1 mustahik penerima pada transaksi penyaluran.');
        }

        $penyaluran = DB::transaction(function () use ($data, $periode, $petugas, $items): Penyaluran {
            $kode = $this->generateKodePenyaluran();

            $penyaluran = Penyaluran::create([
                'kode_penyaluran' => $kode,
                'periode_id' => $periode->id,
                'tanggal_penyaluran' => $data['tanggal_penyaluran'] ?? now(),
                'petugas_id' => $petugas->id,
                'status' => StatusPenyaluranEnum::DIRENCANAKAN->value,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($items as $item) {
                $mustahik = Mustahik::findOrFail($item['mustahik_id']);
                if ($mustahik->status_kelayakan !== StatusKelayakanEnum::LAYAK) {
                    throw new RuntimeException("Mustahik {$mustahik->nama_lengkap} belum berstatus Layak (lakukan survei terlebih dahulu).");
                }

                $bentuk = (string) ($item['bentuk_salur'] ?? 'uang');
                $nominalUang = (float) ($item['nominal_uang'] ?? 0);
                $jumlahBeras = (float) ($item['jumlah_beras_kg'] ?? 0);

                if ($bentuk === 'uang') {
                    if ($nominalUang <= 0) {
                        throw new RuntimeException("Nominal penyaluran uang untuk {$mustahik->nama_lengkap} harus lebih dari Rp 0.");
                    }

                    PenyaluranDetail::create([
                        'penyaluran_id' => $penyaluran->id,
                        'mustahik_id' => $mustahik->id,
                        'asnaf_id' => $item['asnaf_id'] ?? $mustahik->asnaf_id,
                        'jenis_zakat_id' => $item['jenis_zakat_id'],
                        'bentuk_salur' => 'uang',
                        'nominal_uang' => $nominalUang,
                        'jumlah_beras_kg' => 0,
                        'catatan' => $item['catatan'] ?? null,
                    ]);
                } elseif ($bentuk === 'beras') {
                    if ($jumlahBeras <= 0) {
                        throw new RuntimeException("Jumlah penyaluran beras untuk {$mustahik->nama_lengkap} harus lebih dari 0 kg.");
                    }

                    PenyaluranDetail::create([
                        'penyaluran_id' => $penyaluran->id,
                        'mustahik_id' => $mustahik->id,
                        'asnaf_id' => $item['asnaf_id'] ?? $mustahik->asnaf_id,
                        'jenis_zakat_id' => $item['jenis_zakat_id'],
                        'bentuk_salur' => 'beras',
                        'nominal_uang' => 0,
                        'jumlah_beras_kg' => $jumlahBeras,
                        'catatan' => $item['catatan'] ?? null,
                    ]);
                } else {
                    if ($nominalUang <= 0 && $jumlahBeras <= 0) {
                        throw new RuntimeException("Isi minimal nominal uang (Rp) atau jumlah beras (kg) untuk {$mustahik->nama_lengkap}.");
                    }

                    if ($nominalUang > 0) {
                        PenyaluranDetail::create([
                            'penyaluran_id' => $penyaluran->id,
                            'mustahik_id' => $mustahik->id,
                            'asnaf_id' => $item['asnaf_id'] ?? $mustahik->asnaf_id,
                            'jenis_zakat_id' => $item['jenis_zakat_id'],
                            'bentuk_salur' => 'uang',
                            'nominal_uang' => $nominalUang,
                            'jumlah_beras_kg' => 0,
                            'catatan' => $item['catatan'] ?? null,
                        ]);
                    }

                    if ($jumlahBeras > 0) {
                        PenyaluranDetail::create([
                            'penyaluran_id' => $penyaluran->id,
                            'mustahik_id' => $mustahik->id,
                            'asnaf_id' => $item['asnaf_id'] ?? $mustahik->asnaf_id,
                            'jenis_zakat_id' => $item['jenis_zakat_id'],
                            'bentuk_salur' => 'beras',
                            'nominal_uang' => 0,
                            'jumlah_beras_kg' => $jumlahBeras,
                            'catatan' => $item['catatan'] ?? null,
                        ]);
                    }
                }
            }

            return $penyaluran;
        });

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'penyaluran',
            deskripsi: "Membuat rencana penyaluran {$penyaluran->kode_penyaluran} untuk " . count($items) . " penerima.",
            model: $penyaluran,
            dataBaru: $penyaluran->load('details')->toArray()
        );

        if ($langsungSelesai) {
            return $this->selesaikan($penyaluran, $petugas);
        }

        return $penyaluran;
    }

    public function selesaikan(Penyaluran $penyaluran, User $actor): Penyaluran
    {
        $updated = DB::transaction(function () use ($penyaluran, $actor): Penyaluran {
            $locked = Penyaluran::with(['periode', 'details'])
                ->lockForUpdate()
                ->findOrFail($penyaluran->id);

            if ($locked->status !== StatusPenyaluranEnum::DIRENCANAKAN) {
                throw new RuntimeException('Hanya penyaluran berstatus Direncanakan yang dapat diselesaikan.');
            }

            $totalUang = (float) $locked->details
                ->where('bentuk_salur', BentukZakatEnum::UANG)
                ->sum('nominal_uang');

            $totalBerasKg = (float) $locked->details
                ->where('bentuk_salur', BentukZakatEnum::BERAS)
                ->sum('jumlah_beras_kg');

            if ($totalUang > 0) {
                $this->kasService->catatKeluar(
                    periode: $locked->periode,
                    nominal: $totalUang,
                    kategori: 'penyaluran_zakat',
                    keterangan: "Penyaluran zakat {$locked->kode_penyaluran} ({$locked->details->count()} penerima)",
                    referensi: $locked,
                    userId: $actor->id
                );
            }

            if ($totalBerasKg > 0) {
                $this->stokBerasService->catatKeluar(
                    periodeId: $locked->periode_id,
                    jumlahKg: $totalBerasKg,
                    keterangan: "Penyaluran beras {$locked->kode_penyaluran} ({$locked->details->count()} penerima)",
                    referensi: $locked,
                    userId: $actor->id
                );
            }

            $locked->update([
                'status' => StatusPenyaluranEnum::SELESAI->value,
                'selesai_at' => now(),
            ]);

            return $locked->fresh();
        });

        $this->auditLogService->record(
            aksi: 'complete',
            modul: 'penyaluran',
            deskripsi: "Menyelesaikan penyaluran zakat {$updated->kode_penyaluran} oleh {$actor->name}.",
            model: $updated,
            dataBaru: ['status' => $updated->status->value]
        );

        return $updated;
    }

    public function batalkan(Penyaluran $penyaluran, User $actor, ?string $alasan = null): Penyaluran
    {
        $updated = DB::transaction(function () use ($penyaluran, $alasan): Penyaluran {
            $locked = Penyaluran::lockForUpdate()->findOrFail($penyaluran->id);

            if ($locked->status !== StatusPenyaluranEnum::DIRENCANAKAN) {
                throw new RuntimeException('Penyaluran yang sudah selesai atau dibatalkan tidak dapat dibatalkan ulang.');
            }

            $keterangan = $locked->keterangan;
            if ($alasan) {
                $keterangan = trim(($keterangan ? $keterangan . ' | ' : '') . 'Dibatalkan: ' . $alasan);
            }

            $locked->update([
                'status' => StatusPenyaluranEnum::DIBATALKAN->value,
                'keterangan' => $keterangan,
            ]);

            return $locked->fresh();
        });

        $this->auditLogService->record(
            aksi: 'cancel',
            modul: 'penyaluran',
            deskripsi: "Membatalkan rencana penyaluran {$updated->kode_penyaluran} oleh {$actor->name}.",
            model: $updated
        );

        return $updated;
    }

    private function generateKodePenyaluran(): string
    {
        $prefix = 'PNY-' . now()->format('Ymd') . '-';

        $lastKode = Penyaluran::where('kode_penyaluran', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('kode_penyaluran')
            ->value('kode_penyaluran');

        $nextNumber = 1;
        if ($lastKode) {
            $nextNumber = ((int) substr($lastKode, -4)) + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
