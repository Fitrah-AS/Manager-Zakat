<?php

namespace App\Http\Controllers;

use App\Http\Requests\MuzakkiRequest;
use App\Models\Muzakki;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MuzakkiController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));

        $muzakkis = Muzakki::query()
            ->withCount('pembayaran')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('muzakki.index', compact('muzakkis', 'search'));
    }

    public function store(MuzakkiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $muzakki = Muzakki::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'nik' => $data['nik'] ?: null,
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'email' => $data['email'] ?: null,
        ]);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'muzakki',
            deskripsi: "Menambahkan data muzakki {$muzakki->nama_lengkap}.",
            model: $muzakki,
            dataBaru: $muzakki->toArray()
        );

        return redirect()->route('muzakki.index')
            ->with('success', "Data muzakki {$muzakki->nama_lengkap} berhasil ditambahkan.");
    }

    public function show(Muzakki $muzakki): View
    {
        $muzakki->load([
            'pembayaran' => fn ($q) => $q->with(['periode', 'metodePembayaran', 'details.jenisZakat'])->latest('tanggal_bayar'),
        ]);

        return view('muzakki.show', compact('muzakki'));
    }

    public function update(MuzakkiRequest $request, Muzakki $muzakki): RedirectResponse
    {
        $data = $request->validated();
        $dataLama = $muzakki->toArray();

        $muzakki->update([
            'nama_lengkap' => $data['nama_lengkap'],
            'nik' => $data['nik'] ?: null,
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'email' => $data['email'] ?: null,
        ]);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'muzakki',
            deskripsi: "Memperbarui data muzakki {$muzakki->nama_lengkap}.",
            model: $muzakki,
            dataLama: $dataLama,
            dataBaru: $muzakki->fresh()->toArray()
        );

        return redirect()->route('muzakki.index')
            ->with('success', "Data muzakki {$muzakki->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(Muzakki $muzakki): RedirectResponse
    {
        if ($muzakki->pembayaran()->exists()) {
            return redirect()->route('muzakki.index')
                ->with('error', 'Data muzakki tidak dapat dihapus karena sudah memiliki riwayat transaksi pembayaran zakat.');
        }

        $dataLama = $muzakki->toArray();
        $nama = $muzakki->nama_lengkap;
        $muzakki->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'muzakki',
            deskripsi: "Menghapus data muzakki {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('muzakki.index')
            ->with('success', "Data muzakki {$nama} berhasil dihapus.");
    }
}
