<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MetodePembayaranRequest;
use App\Models\MetodePembayaran;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MetodePembayaranController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $metodes = MetodePembayaran::orderBy('tipe')->orderBy('nama_metode')->paginate(15);

        return view('admin.metode-pembayaran.index', compact('metodes'));
    }

    public function store(MetodePembayaranRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $metode = MetodePembayaran::create([
            'kode' => strtoupper(trim($data['kode'])),
            'nama_metode' => $data['nama_metode'],
            'tipe' => $data['tipe'],
            'nama_bank' => $data['nama_bank'] ?? null,
            'no_rekening' => $data['no_rekening'] ?? null,
            'atas_nama' => $data['atas_nama'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'metode_pembayaran',
            deskripsi: "Menambahkan metode pembayaran {$metode->nama_metode}.",
            model: $metode,
            dataBaru: $metode->toArray()
        );

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', "Metode pembayaran {$metode->nama_metode} berhasil ditambahkan.");
    }

    public function update(MetodePembayaranRequest $request, MetodePembayaran $metodePembayaran): RedirectResponse
    {
        $data = $request->validated();
        $dataLama = $metodePembayaran->toArray();

        $metodePembayaran->update([
            'kode' => strtoupper(trim($data['kode'])),
            'nama_metode' => $data['nama_metode'],
            'tipe' => $data['tipe'],
            'nama_bank' => $data['nama_bank'] ?? null,
            'no_rekening' => $data['no_rekening'] ?? null,
            'atas_nama' => $data['atas_nama'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'metode_pembayaran',
            deskripsi: "Memperbarui metode pembayaran {$metodePembayaran->nama_metode}.",
            model: $metodePembayaran,
            dataLama: $dataLama,
            dataBaru: $metodePembayaran->fresh()->toArray()
        );

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', "Metode pembayaran {$metodePembayaran->nama_metode} berhasil diperbarui.");
    }

    public function destroy(MetodePembayaran $metodePembayaran): RedirectResponse
    {
        if ($metodePembayaran->pembayaran()->exists()) {
            return redirect()->route('admin.metode-pembayaran.index')
                ->with('error', 'Metode pembayaran tidak dapat dihapus karena sudah digunakan pada transaksi pembayaran.');
        }

        $dataLama = $metodePembayaran->toArray();
        $nama = $metodePembayaran->nama_metode;
        $metodePembayaran->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'metode_pembayaran',
            deskripsi: "Menghapus metode pembayaran {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', "Metode pembayaran {$nama} berhasil dihapus.");
    }
}
