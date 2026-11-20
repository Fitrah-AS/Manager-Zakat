<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JenisZakatRequest;
use App\Models\JenisZakat;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JenisZakatController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $jenisZakats = JenisZakat::orderBy('kategori')->orderBy('nama_jenis')->paginate(15);

        return view('admin.jenis-zakat.index', compact('jenisZakats'));
    }

    public function store(JenisZakatRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $jenisZakat = JenisZakat::create([
            'kode' => strtoupper(trim($data['kode'])),
            'nama_jenis' => $data['nama_jenis'],
            'kategori' => $data['kategori'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'jenis_zakat',
            deskripsi: "Menambahkan jenis zakat {$jenisZakat->nama_jenis}.",
            model: $jenisZakat,
            dataBaru: $jenisZakat->toArray()
        );

        return redirect()->route('admin.jenis-zakat.index')
            ->with('success', "Jenis zakat {$jenisZakat->nama_jenis} berhasil ditambahkan.");
    }

    public function update(JenisZakatRequest $request, JenisZakat $jenisZakat): RedirectResponse
    {
        $data = $request->validated();
        $dataLama = $jenisZakat->toArray();

        $jenisZakat->update([
            'kode' => strtoupper(trim($data['kode'])),
            'nama_jenis' => $data['nama_jenis'],
            'kategori' => $data['kategori'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'jenis_zakat',
            deskripsi: "Memperbarui jenis zakat {$jenisZakat->nama_jenis}.",
            model: $jenisZakat,
            dataLama: $dataLama,
            dataBaru: $jenisZakat->fresh()->toArray()
        );

        return redirect()->route('admin.jenis-zakat.index')
            ->with('success', "Jenis zakat {$jenisZakat->nama_jenis} berhasil diperbarui.");
    }

    public function destroy(JenisZakat $jenisZakat): RedirectResponse
    {
        if ($jenisZakat->pembayaranDetails()->exists() || $jenisZakat->penyaluranDetails()->exists()) {
            return redirect()->route('admin.jenis-zakat.index')
                ->with('error', 'Jenis zakat tidak dapat dihapus karena sudah digunakan pada transaksi.');
        }

        $dataLama = $jenisZakat->toArray();
        $nama = $jenisZakat->nama_jenis;
        $jenisZakat->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'jenis_zakat',
            deskripsi: "Menghapus jenis zakat {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('admin.jenis-zakat.index')
            ->with('success', "Jenis zakat {$nama} berhasil dihapus.");
    }
}
