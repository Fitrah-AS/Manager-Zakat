<?php

namespace App\Http\Controllers;

use App\Enums\StatusKelayakanEnum;
use App\Http\Requests\StorePenyaluranRequest;
use App\Models\Asnaf;
use App\Models\JenisZakat;
use App\Models\Mustahik;
use App\Models\Penyaluran;
use App\Models\Periode;
use App\Services\KasService;
use App\Services\PenyaluranService;
use App\Services\StokBerasService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class PenyaluranController extends Controller
{
    public function __construct(
        private readonly PenyaluranService $penyaluranService,
        private readonly KasService $kasService,
        private readonly StokBerasService $stokBerasService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', '');
        $periodeId = $request->input('periode_id', '');

        $penyalurans = Penyaluran::query()
            ->with(['periode', 'petugas', 'details'])
            ->withCount('details')
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->when($periodeId !== '', fn ($q) => $q->where('periode_id', $periodeId))
            ->latest('tanggal_penyaluran')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $periodes = Periode::orderByDesc('is_active')->orderByDesc('tanggal_mulai')->get();

        return view('penyaluran.index', compact('penyalurans', 'periodes', 'status', 'periodeId'));
    }

    public function create(): View
    {
        $periodeAktif = Periode::aktif()->first();
        $mustahiks = Mustahik::with('asnaf')
            ->where('status_kelayakan', StatusKelayakanEnum::LAYAK->value)
            ->orderBy('nama_lengkap')
            ->get();
        $asnafs = Asnaf::where('is_active', true)->orderBy('urutan')->get();
        $jenisZakats = JenisZakat::where('is_active', true)->orderBy('kategori')->orderBy('nama_jenis')->get();

        $saldoKasUang = $this->kasService->getSaldoReadOnly();
        $stokBerasKg = $this->stokBerasService->getStokReadOnlyKg();

        return view('penyaluran.create', compact(
            'periodeAktif',
            'mustahiks',
            'asnafs',
            'jenisZakats',
            'saldoKasUang',
            'stokBerasKg'
        ));
    }

    public function store(StorePenyaluranRequest $request): RedirectResponse
    {
        try {
            $penyaluran = $this->penyaluranService->buatPenyaluran(
                $request->validated(),
                auth()->user(),
                $request->boolean('langsung_selesai')
            );
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('penyaluran.show', $penyaluran)
            ->with('success', "Penyaluran {$penyaluran->kode_penyaluran} berhasil disimpan dengan status {$penyaluran->status->label()}.");
    }

    public function show(Penyaluran $penyaluran): View
    {
        $penyaluran->load(['periode', 'petugas', 'details.mustahik', 'details.asnaf', 'details.jenisZakat']);

        return view('penyaluran.show', compact('penyaluran'));
    }

    public function selesai(Penyaluran $penyaluran): RedirectResponse
    {
        try {
            $this->penyaluranService->selesaikan($penyaluran, auth()->user());
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('penyaluran.show', $penyaluran)
            ->with('success', "Penyaluran {$penyaluran->kode_penyaluran} telah diselesaikan dan kas/stok beras otomatis diperbarui.");
    }

    public function batal(Request $request, Penyaluran $penyaluran): RedirectResponse
    {
        try {
            $this->penyaluranService->batalkan($penyaluran, auth()->user(), $request->input('alasan'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('penyaluran.show', $penyaluran)
            ->with('success', "Rencana penyaluran {$penyaluran->kode_penyaluran} telah dibatalkan.");
    }

    public function cetakBukti(Penyaluran $penyaluran): View
    {
        $penyaluran->load(['periode', 'petugas', 'details.mustahik', 'details.asnaf', 'details.jenisZakat']);

        return view('penyaluran.bukti', compact('penyaluran'));
    }

    public function downloadPdf(Penyaluran $penyaluran): Response
    {
        $penyaluran->load(['periode', 'petugas', 'details.mustahik', 'details.asnaf', 'details.jenisZakat']);
        $pdf = Pdf::loadView('penyaluran.bukti-pdf', compact('penyaluran'));

        return $pdf->download("bukti-penyaluran-{$penyaluran->kode_penyaluran}.pdf");
    }
}
