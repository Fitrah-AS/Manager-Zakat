<?php

namespace App\Http\Controllers;

use App\Http\Requests\MustahikRequest;
use App\Models\Asnaf;
use App\Models\Mustahik;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MustahikController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $asnafId = $request->input('asnaf_id', '');
        $statusKelayakan = $request->input('status_kelayakan', '');

        $mustahiks = Mustahik::query()
            ->with(['asnaf', 'surveiTerakhir'])
            ->withCount('survei')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%");
                });
            })
            ->when($asnafId !== '', fn ($q) => $q->where('asnaf_id', $asnafId))
            ->when($statusKelayakan !== '', fn ($q) => $q->where('status_kelayakan', $statusKelayakan))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $asnafs = Asnaf::where('is_active', true)->orderBy('urutan')->get();

        return view('mustahik.index', compact('mustahiks', 'asnafs', 'search', 'asnafId', 'statusKelayakan'));
    }

    public function store(MustahikRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $mustahik = Mustahik::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'nik' => $data['nik'] ?: null,
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'asnaf_id' => $data['asnaf_id'],
            'status_kelayakan' => $data['status_kelayakan'],
            'latitude' => $data['latitude'] !== null && $data['latitude'] !== '' ? $data['latitude'] : null,
            'longitude' => $data['longitude'] !== null && $data['longitude'] !== '' ? $data['longitude'] : null,
        ]);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'mustahik',
            deskripsi: "Menambahkan data mustahik {$mustahik->nama_lengkap}.",
            model: $mustahik,
            dataBaru: $mustahik->toArray()
        );

        return redirect()->route('mustahik.index')
            ->with('success', "Data mustahik {$mustahik->nama_lengkap} berhasil ditambahkan.");
    }

    public function show(Mustahik $mustahik): View
    {
        $mustahik->load([
            'asnaf',
            'survei' => fn ($q) => $q->with('petugas')->latest('tanggal_survei')->latest('id'),
            'penyaluranDetails' => fn ($q) => $q->with(['penyaluran.periode', 'jenisZakat'])->latest('id'),
        ]);

        return view('mustahik.show', compact('mustahik'));
    }

    public function update(MustahikRequest $request, Mustahik $mustahik): RedirectResponse
    {
        $data = $request->validated();
        $dataLama = $mustahik->toArray();

        $mustahik->update([
            'nama_lengkap' => $data['nama_lengkap'],
            'nik' => $data['nik'] ?: null,
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp'],
            'asnaf_id' => $data['asnaf_id'],
            'status_kelayakan' => $data['status_kelayakan'],
            'latitude' => $data['latitude'] !== null && $data['latitude'] !== '' ? $data['latitude'] : null,
            'longitude' => $data['longitude'] !== null && $data['longitude'] !== '' ? $data['longitude'] : null,
        ]);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'mustahik',
            deskripsi: "Memperbarui data mustahik {$mustahik->nama_lengkap}.",
            model: $mustahik,
            dataLama: $dataLama,
            dataBaru: $mustahik->fresh()->toArray()
        );

        return redirect()->route('mustahik.index')
            ->with('success', "Data mustahik {$mustahik->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(Mustahik $mustahik): RedirectResponse
    {
        if ($mustahik->penyaluranDetails()->exists()) {
            return redirect()->route('mustahik.index')
                ->with('error', 'Data mustahik tidak dapat dihapus karena sudah tercatat pada riwayat penyaluran zakat.');
        }

        $dataLama = $mustahik->toArray();
        $nama = $mustahik->nama_lengkap;
        $mustahik->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'mustahik',
            deskripsi: "Menghapus data mustahik {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('mustahik.index')
            ->with('success', "Data mustahik {$nama} berhasil dihapus.");
    }
}
