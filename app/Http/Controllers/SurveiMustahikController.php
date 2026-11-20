<?php

namespace App\Http\Controllers;

use App\Enums\StatusKelayakanEnum;
use App\Http\Requests\SurveiMustahikRequest;
use App\Models\Mustahik;
use App\Models\SurveiMustahik;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class SurveiMustahikController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function store(SurveiMustahikRequest $request, Mustahik $mustahik): RedirectResponse
    {
        $data = $request->validated();

        $survei = DB::transaction(function () use ($data, $mustahik): SurveiMustahik {
            $survei = SurveiMustahik::create([
                'mustahik_id' => $mustahik->id,
                'petugas_id' => auth()->id(),
                'tanggal_survei' => $data['tanggal_survei'],
                'kondisi_ekonomi' => $data['kondisi_ekonomi'],
                'jumlah_tanggungan' => $data['jumlah_tanggungan'],
                'hasil_survei' => $data['hasil_survei'],
                'catatan_petugas' => $data['catatan_petugas'] ?? null,
            ]);

            $statusKelayakan = match ($data['hasil_survei']) {
                'layak' => StatusKelayakanEnum::LAYAK->value,
                'tidak_layak' => StatusKelayakanEnum::TIDAK_LAYAK->value,
                default => StatusKelayakanEnum::MENUNGGU_SURVEI->value,
            };

            $mustahik->update(['status_kelayakan' => $statusKelayakan]);

            return $survei;
        });

        $this->auditLogService->record(
            aksi: 'survei',
            modul: 'survei_mustahik',
            deskripsi: "Mencatat survei kelayakan mustahik {$mustahik->nama_lengkap} dengan hasil '{$survei->hasil_survei}'.",
            model: $survei,
            dataBaru: $survei->toArray()
        );

        return redirect()->route('mustahik.show', $mustahik)
            ->with('success', "Hasil survei untuk {$mustahik->nama_lengkap} berhasil disimpan dan status kelayakan diperbarui.");
    }
}
