<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AsnafRequest;
use App\Models\Asnaf;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AsnafController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $asnafs = Asnaf::withCount('mustahik')->orderBy('urutan')->paginate(15);

        return view('admin.asnaf.index', compact('asnafs'));
    }

    public function store(AsnafRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $asnaf = Asnaf::create([
            'kode' => strtoupper(trim($data['kode'])),
            'nama_asnaf' => $data['nama_asnaf'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'urutan' => $data['urutan'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'asnaf',
            deskripsi: "Menambahkan asnaf {$asnaf->nama_asnaf} ({$asnaf->kode}).",
            model: $asnaf,
            dataBaru: $asnaf->toArray()
        );

        return redirect()->route('admin.asnaf.index')
            ->with('success', "Data asnaf {$asnaf->nama_asnaf} berhasil ditambahkan.");
    }

    public function update(AsnafRequest $request, Asnaf $asnaf): RedirectResponse
    {
        $data = $request->validated();
        $dataLama = $asnaf->toArray();

        $asnaf->update([
            'kode' => strtoupper(trim($data['kode'])),
            'nama_asnaf' => $data['nama_asnaf'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'urutan' => $data['urutan'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'asnaf',
            deskripsi: "Memperbarui asnaf {$asnaf->nama_asnaf}.",
            model: $asnaf,
            dataLama: $dataLama,
            dataBaru: $asnaf->fresh()->toArray()
        );

        return redirect()->route('admin.asnaf.index')
            ->with('success', "Data asnaf {$asnaf->nama_asnaf} berhasil diperbarui.");
    }

    public function destroy(Asnaf $asnaf): RedirectResponse
    {
        if ($asnaf->mustahik()->exists() || $asnaf->penyaluranDetails()->exists()) {
            return redirect()->route('admin.asnaf.index')
                ->with('error', 'Data asnaf tidak dapat dihapus karena masih terhubung dengan data mustahik atau penyaluran.');
        }

        $dataLama = $asnaf->toArray();
        $nama = $asnaf->nama_asnaf;
        $asnaf->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'asnaf',
            deskripsi: "Menghapus asnaf {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('admin.asnaf.index')
            ->with('success', "Data asnaf {$nama} berhasil dihapus.");
    }
}
