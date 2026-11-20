<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranRequest;
use App\Models\JenisZakat;
use App\Models\MetodePembayaran;
use App\Models\Muzakki;
use App\Models\Nisab;
use App\Models\Pembayaran;
use App\Models\Periode;
use App\Services\PembayaranService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class PembayaranController extends Controller
{
    public function __construct(
        private readonly PembayaranService $pembayaranService
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $status = $request->input('status', '');
        $periodeId = $request->input('periode_id', '');

        $pembayarans = Pembayaran::query()
            ->with(['muzakki', 'periode', 'metodePembayaran', 'petugasInput', 'details.jenisZakat'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('kode_transaksi', 'like', "%{$search}%")
                        ->orWhereHas('muzakki', fn ($mq) => $mq->where('nama_lengkap', 'like', "%{$search}%"));
                });
            })
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->when($periodeId !== '', fn ($q) => $q->where('periode_id', $periodeId))
            ->latest('tanggal_bayar')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $periodes = Periode::orderByDesc('is_active')->orderByDesc('tanggal_mulai')->get();

        return view('pembayaran.index', compact('pembayarans', 'periodes', 'search', 'status', 'periodeId'));
    }

    public function create(): View
    {
        $periodeAktif = Periode::aktif()->with('tarifFitrah')->first();
        $muzakkis = Muzakki::orderBy('nama_lengkap')->get();
        $jenisZakats = JenisZakat::where('is_active', true)->orderBy('kategori')->orderBy('nama_jenis')->get();
        $metodes = MetodePembayaran::where('is_active', true)->orderBy('tipe')->get();
        $nisabs = Nisab::where('is_active', true)->get()->keyBy('jenis_harta');

        return view('pembayaran.create', compact('periodeAktif', 'muzakkis', 'jenisZakats', 'metodes', 'nisabs'));
    }

    public function store(StorePembayaranRequest $request): RedirectResponse
    {
        try {
            $pembayaran = $this->pembayaranService->buatTransaksi(
                $request->validated(),
                $request->file('bukti_transfer')
            );
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $pesanStatus = $pembayaran->status->value === 'terverifikasi'
            ? 'dan langsung terverifikasi (Tunai).'
            : 'dengan status Menunggu Verifikasi Admin.';

        return redirect()->route('pembayaran.show', $pembayaran)
            ->with('success', "Transaksi {$pembayaran->kode_transaksi} berhasil dicatat {$pesanStatus}");
    }

    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->load([
            'periode',
            'muzakki',
            'metodePembayaran',
            'petugasInput',
            'verifikator',
            'details.jenisZakat',
            'details.jiwa',
            'details.perhitunganZakatMal',
        ]);

        return view('pembayaran.show', compact('pembayaran'));
    }

    public function cetakStruk(Pembayaran $pembayaran): View
    {
        $pembayaran->load([
            'periode',
            'muzakki',
            'metodePembayaran',
            'petugasInput',
            'verifikator',
            'details.jenisZakat',
            'details.jiwa',
        ]);

        return view('pembayaran.struk', compact('pembayaran'));
    }

    public function downloadPdf(Pembayaran $pembayaran): Response
    {
        $pembayaran->load([
            'periode',
            'muzakki',
            'metodePembayaran',
            'petugasInput',
            'verifikator',
            'details.jenisZakat',
            'details.jiwa',
        ]);

        $pdf = Pdf::loadView('pembayaran.struk-pdf', compact('pembayaran'));

        return $pdf->download("bukti-zakat-{$pembayaran->kode_transaksi}.pdf");
    }

    public function lihatBukti(Pembayaran $pembayaran): BinaryFileResponse
    {
        if (! $pembayaran->bukti_transfer || ! Storage::disk('local')->exists($pembayaran->bukti_transfer)) {
            abort(404, 'File bukti transfer tidak ditemukan.');
        }

        return response()->file(Storage::disk('local')->path($pembayaran->bukti_transfer));
    }
}
