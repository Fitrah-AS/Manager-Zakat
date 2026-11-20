<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NisabRequest;
use App\Models\Nisab;
use App\Models\Periode;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NisabController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $nisabs = Nisab::with('periode')->orderBy('jenis_harta')->paginate(15);
        $periodes = Periode::orderByDesc('is_active')->orderByDesc('tanggal_mulai')->get();

        return view('admin.nisab.index', compact('nisabs', 'periodes'));
    }

    public function store(NisabRequest $request): RedirectResponse
    {
        $payload = $this->preparePayload($request);
        $nisab = Nisab::create($payload);

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'nisab',
            deskripsi: "Menambahkan standar nisab {$nisab->nama_standar}.",
            model: $nisab,
            dataBaru: $nisab->toArray()
        );

        return redirect()->route('admin.nisab.index')
            ->with('success', "Standar nisab {$nisab->nama_standar} berhasil ditambahkan.");
    }

    public function update(NisabRequest $request, Nisab $nisab): RedirectResponse
    {
        $dataLama = $nisab->toArray();
        $payload = $this->preparePayload($request);
        $nisab->update($payload);

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'nisab',
            deskripsi: "Memperbarui standar nisab {$nisab->nama_standar}.",
            model: $nisab,
            dataLama: $dataLama,
            dataBaru: $nisab->fresh()->toArray()
        );

        return redirect()->route('admin.nisab.index')
            ->with('success', "Standar nisab {$nisab->nama_standar} berhasil diperbarui.");
    }

    public function destroy(Nisab $nisab): RedirectResponse
    {
        if ($nisab->perhitunganZakatMal()->exists()) {
            return redirect()->route('admin.nisab.index')
                ->with('error', 'Standar nisab tidak dapat dihapus karena sudah digunakan pada riwayat perhitungan zakat mal.');
        }

        $dataLama = $nisab->toArray();
        $nama = $nisab->nama_standar;
        $nisab->delete();

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'nisab',
            deskripsi: "Menghapus standar nisab {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('admin.nisab.index')
            ->with('success', "Standar nisab {$nama} berhasil dihapus.");
    }

    private function preparePayload(NisabRequest $request): array
    {
        $data = $request->validated();
        $hargaEmas = (float) ($data['harga_emas_per_gram'] ?? 0);
        $gramNisab = (float) ($data['gram_emas_nisab'] ?? 85);
        $nilaiNisabUang = (float) ($data['nilai_nisab_uang'] ?? 0);

        if ($data['jenis_harta'] !== 'pertanian' && $hargaEmas > 0 && $gramNisab > 0) {
            $nilaiNisabUang = $hargaEmas * $gramNisab;
        }

        return [
            'periode_id' => $data['periode_id'] ?: null,
            'jenis_harta' => $data['jenis_harta'],
            'nama_standar' => $data['nama_standar'],
            'basis_nisab' => $data['basis_nisab'],
            'harga_emas_per_gram' => $hargaEmas,
            'gram_emas_nisab' => $data['jenis_harta'] === 'pertanian' ? 0 : $gramNisab,
            'nisab_pertanian_kg' => $data['jenis_harta'] === 'pertanian' ? ($data['nisab_pertanian_kg'] ?? 653) : null,
            'nilai_nisab_uang' => $nilaiNisabUang,
            'kadar_zakat_persen' => $data['kadar_zakat_persen'],
            'ketentuan_haul' => $data['ketentuan_haul'],
            'catatan' => $data['catatan'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];
    }
}
