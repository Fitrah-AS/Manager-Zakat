<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Nisab;
use App\Models\Periode;
use App\Services\ZakatCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KalkulatorZakatController extends Controller
{
    public function __construct(
        private readonly ZakatCalculatorService $calculatorService
    ) {}

    public function index(): View
    {
        $periodeAktif = Periode::aktif()->with('tarifFitrah')->first();
        $nisabs = Nisab::query()
            ->where('is_active', true)
            ->when($periodeAktif, fn ($q) => $q->where(fn ($sub) => $sub->where('periode_id', $periodeAktif->id)->orWhereNull('periode_id')))
            ->orderBy('id')
            ->get()
            ->keyBy('jenis_harta');

        $hargaEmasDefault = (float) ($nisabs->get('emas_perak')?->harga_emas_per_gram ?? 1500000);

        return view('public.kalkulator', compact('periodeAktif', 'nisabs', 'hargaEmasDefault'));
    }

    public function hitung(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jenis_harta' => ['required', 'in:emas_perak,penghasilan,perdagangan,tabungan_investasi,pertanian'],
            'nilai_harta_kotor' => ['nullable', 'numeric', 'min:0'],
            'hutang_jatuh_tempo' => ['nullable', 'numeric', 'min:0'],
            'harga_emas_per_gram' => ['nullable', 'numeric', 'min:1'],
            'sudah_haul' => ['nullable'],
            'metode_penghasilan' => ['nullable', 'in:bruto,neto'],
            'periode_penghasilan' => ['nullable', 'in:bulanan,tahunan'],
            'jenis_panen' => ['nullable', 'in:gabah,beras'],
            'berat_panen_kg' => ['nullable', 'numeric', 'min:0'],
            'harga_per_kg' => ['nullable', 'numeric', 'min:0'],
            'jenis_irigasi' => ['nullable', 'in:berbiaya,alami'],
        ], [
            'jenis_harta.required' => 'Pilih jenis harta zakat mal terlebih dahulu.',
            'jenis_harta.in' => 'Jenis harta zakat mal tidak valid.',
        ]);

        $periodeAktif = Periode::aktif()->first();
        $hasil = $this->calculatorService->hitungMal($validated, $periodeAktif);

        return response()->json([
            'success' => true,
            'data' => $hasil,
            'disclaimer' => 'Kalkulator ini hanya sebagai alat bantu perhitungan administratif zakat, bukan fatwa agama.',
        ]);
    }
}
