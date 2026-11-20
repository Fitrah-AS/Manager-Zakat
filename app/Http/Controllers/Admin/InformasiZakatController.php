<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InformasiZakatRequest;
use App\Models\InformasiZakat;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InformasiZakatController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $informasis = InformasiZakat::with('updatedBy')->latest('updated_at')->paginate(12);

        return view('admin.informasi.index', compact('informasis'));
    }

    public function store(InformasiZakatRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $slugBase = Str::slug($data['judul']);
        $slug = $slugBase;
        $counter = 1;
        while (InformasiZakat::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $info = InformasiZakat::create([
            'judul' => $data['judul'],
            'slug' => $slug,
            'kategori' => $data['kategori'],
            'konten' => $data['konten'],
            'is_published' => $request->boolean('is_published', true),
            'updated_by' => auth()->id(),
        ]);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'informasi_zakat',
            deskripsi: "Menambahkan informasi publik '{$info->judul}'.",
            model: $info,
            dataBaru: $info->toArray()
        );

        return redirect()->route('admin.informasi.index')
            ->with('success', "Informasi '{$info->judul}' berhasil ditambahkan.");
    }

    public function update(InformasiZakatRequest $request, InformasiZakat $informasi): RedirectResponse
    {
        $data = $request->validated();
        $dataLama = $informasi->toArray();

        $informasi->update([
            'judul' => $data['judul'],
            'kategori' => $data['kategori'],
            'konten' => $data['konten'],
            'is_published' => $request->boolean('is_published'),
            'updated_by' => auth()->id(),
        ]);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'informasi_zakat',
            deskripsi: "Memperbarui informasi publik '{$informasi->judul}'.",
            model: $informasi,
            dataLama: $dataLama,
            dataBaru: $informasi->fresh()->toArray()
        );

        return redirect()->route('admin.informasi.index')
            ->with('success', "Informasi '{$informasi->judul}' berhasil diperbarui.");
    }

    public function destroy(InformasiZakat $informasi): RedirectResponse
    {
        $dataLama = $informasi->toArray();
        $judul = $informasi->judul;
        $informasi->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'informasi_zakat',
            deskripsi: "Menghapus informasi publik '{$judul}'.",
            dataLama: $dataLama
        );

        return redirect()->route('admin.informasi.index')
            ->with('success', "Informasi '{$judul}' berhasil dihapus.");
    }
}
