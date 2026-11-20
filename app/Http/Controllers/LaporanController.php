<?php

namespace App\Http\Controllers;

use App\Models\Asnaf;
use App\Models\Periode;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function __construct(
        private readonly LaporanService $laporanService
    ) {}

    public function index(): View
    {
        $daftarLaporan = LaporanService::JENIS_LAPORAN;
        $periodes = Periode::orderByDesc('is_active')->orderByDesc('tanggal_mulai')->get();

        return view('laporan.index', compact('daftarLaporan', 'periodes'));
    }

    public function show(Request $request, string $jenis): View
    {
        abort_unless(array_key_exists($jenis, LaporanService::JENIS_LAPORAN), 404);

        $filters = $request->only(['periode_id', 'tanggal_mulai', 'tanggal_selesai', 'asnaf_id', 'q']);
        $laporan = $this->laporanService->generate($jenis, $filters);

        $periodes = Periode::orderByDesc('is_active')->orderByDesc('tanggal_mulai')->get();
        $asnafs = Asnaf::orderBy('urutan')->get();

        return view('laporan.show', compact('laporan', 'filters', 'periodes', 'asnafs'));
    }

    public function cetak(Request $request, string $jenis): View
    {
        abort_unless(array_key_exists($jenis, LaporanService::JENIS_LAPORAN), 404);

        $filters = $request->only(['periode_id', 'tanggal_mulai', 'tanggal_selesai', 'asnaf_id', 'q']);
        $laporan = $this->laporanService->generate($jenis, $filters);

        return view('laporan.cetak', compact('laporan', 'filters'));
    }

    public function exportPdf(Request $request, string $jenis): Response
    {
        abort_unless(array_key_exists($jenis, LaporanService::JENIS_LAPORAN), 404);

        $filters = $request->only(['periode_id', 'tanggal_mulai', 'tanggal_selesai', 'asnaf_id', 'q']);
        $laporan = $this->laporanService->generate($jenis, $filters);

        $pdf = Pdf::loadView('laporan.pdf.template', compact('laporan', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("laporan-{$jenis}-" . now()->format('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request, string $jenis): StreamedResponse
    {
        abort_unless(array_key_exists($jenis, LaporanService::JENIS_LAPORAN), 404);

        $filters = $request->only(['periode_id', 'tanggal_mulai', 'tanggal_selesai', 'asnaf_id', 'q']);

        return $this->laporanService->exportExcel($jenis, $filters);
    }
}
