<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStokBerasRequest;
use App\Models\MutasiStokBeras;
use App\Models\Periode;
use App\Services\AuditLogService;
use App\Services\StokBerasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class StokBerasController extends Controller
{
    public function __construct(
        private readonly StokBerasService $stokBerasService,
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(Request $request): View
    {
        $periodeId = $request->input('periode_id', '');
        $tipe = $request->input('tipe', '');

        $stokSaatIniKg = $this->stokBerasService->getStokReadOnlyKg();
        $totalMasukKg = (float) MutasiStokBeras::where('tipe', 'masuk')
            ->when($periodeId !== '', fn ($q) => $q->where('periode_id', $periodeId))
            ->sum('jumlah_kg');
        $totalKeluarKg = (float) MutasiStokBeras::where('tipe', 'keluar')
            ->when($periodeId !== '', fn ($q) => $q->where('periode_id', $periodeId))
            ->sum('jumlah_kg');

        $mutasis = MutasiStokBeras::with(['periode', 'createdBy'])
            ->when($periodeId !== '', fn ($q) => $q->where('periode_id', $periodeId))
            ->when($tipe !== '', fn ($q) => $q->where('tipe', $tipe))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $periodes = Periode::orderByDesc('is_active')->orderByDesc('tanggal_mulai')->get();

        return view('stok-beras.index', compact(
            'stokSaatIniKg',
            'totalMasukKg',
            'totalKeluarKg',
            'mutasis',
            'periodes',
            'periodeId',
            'tipe'
        ));
    }

    public function store(StoreStokBerasRequest $request): RedirectResponse
    {
        $periodeAktif = Periode::aktif()->first();
        if (! $periodeAktif) {
            return back()->with('error', 'Belum ada periode zakat aktif.');
        }

        $data = $request->validated();

        try {
            $masuk = DB::transaction(function () use ($periodeAktif, $data) {
                return $this->stokBerasService->catatMasukManual(
                    periode: $periodeAktif,
                    jumlahKg: (float) $data['jumlah_kg'],
                    sumber: $data['sumber'],
                    tanggalMasuk: $data['tanggal_masuk'],
                    keterangan: $data['keterangan'],
                    userId: auth()->id()
                );
            });

            $this->auditLogService->record(
                aksi: 'create',
                modul: 'stok_beras',
                deskripsi: "Mencatat pemasukan beras manual {$data['jumlah_kg']} kg ({$data['sumber']}).",
                model: $masuk,
                dataBaru: $masuk->toArray()
            );
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('stok-beras.index')
            ->with('success', "Pemasukan stok beras {$data['jumlah_kg']} kg berhasil dicatat.");
    }
}
