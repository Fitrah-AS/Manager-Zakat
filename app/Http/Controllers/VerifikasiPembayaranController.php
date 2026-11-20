<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifikasiPembayaranRequest;
use App\Models\Pembayaran;
use App\Services\VerifikasiPembayaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class VerifikasiPembayaranController extends Controller
{
    public function __construct(
        private readonly VerifikasiPembayaranService $verifikasiService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', 'menunggu_verifikasi');

        $pembayarans = Pembayaran::query()
            ->with(['muzakki', 'metodePembayaran', 'petugasInput', 'verifikator', 'details.jenisZakat'])
            ->when($status !== 'semua' && $status !== '', fn ($q) => $q->where('status', $status))
            ->latest('tanggal_bayar')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $jumlahMenunggu = Pembayaran::where('status', 'menunggu_verifikasi')->count();

        return view('verifikasi.index', compact('pembayarans', 'status', 'jumlahMenunggu'));
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

        return view('verifikasi.show', compact('pembayaran'));
    }

    public function terima(Pembayaran $pembayaran): RedirectResponse
    {
        try {
            $this->verifikasiService->terima($pembayaran, auth()->user());
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('verifikasi.index')
            ->with('success', "Pembayaran {$pembayaran->kode_transaksi} berhasil diverifikasi dan tercatat sebagai penerimaan resmi.");
    }

    public function tolak(VerifikasiPembayaranRequest $request, Pembayaran $pembayaran): RedirectResponse
    {
        try {
            $this->verifikasiService->tolak(
                $pembayaran,
                auth()->user(),
                $request->validated('alasan_penolakan')
            );
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('verifikasi.index')
            ->with('success', "Pembayaran {$pembayaran->kode_transaksi} telah ditolak beserta alasan.");
    }
}
