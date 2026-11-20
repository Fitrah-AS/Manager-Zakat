<?php

namespace App\Http\Controllers\Public;

use App\Enums\BentukZakatEnum;
use App\Enums\StatusKelayakanEnum;
use App\Enums\StatusPembayaranEnum;
use App\Enums\StatusPenyaluranEnum;
use App\Http\Controllers\Controller;
use App\Models\Asnaf;
use App\Models\InformasiZakat;
use App\Models\Mustahik;
use App\Models\Muzakki;
use App\Models\Nisab;
use App\Models\Pembayaran;
use App\Models\PenyaluranDetail;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $periodeAktif = Periode::aktif()->with('tarifFitrah')->first();

        $nisabs = Nisab::query()
            ->where('is_active', true)
            ->when($periodeAktif, fn ($q) => $q->where(fn ($sub) => $sub->where('periode_id', $periodeAktif->id)->orWhereNull('periode_id')))
            ->orderBy('id')
            ->get();

        $informasi = InformasiZakat::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->get()
            ->groupBy('kategori');

        $pembayaranQuery = Pembayaran::query()
            ->where('status', StatusPembayaranEnum::TERVERIFIKASI->value)
            ->when($periodeAktif, fn ($q) => $q->where('periode_id', $periodeAktif->id));

        $totalPenerimaanUang = (float) (clone $pembayaranQuery)->sum('total_uang');
        $totalPenerimaanBerasKg = (float) (clone $pembayaranQuery)->sum('total_beras_kg');
        $totalTransaksiTerverifikasi = (clone $pembayaranQuery)->count();

        $penyaluranDetailQuery = PenyaluranDetail::query()
            ->whereHas('penyaluran', function ($q) use ($periodeAktif): void {
                $q->where('status', StatusPenyaluranEnum::SELESAI->value)
                    ->when($periodeAktif, fn ($sub) => $sub->where('periode_id', $periodeAktif->id));
            });

        $totalDisalurkanUang = (float) (clone $penyaluranDetailQuery)
            ->where('bentuk_salur', BentukZakatEnum::UANG->value)
            ->sum('nominal_uang');
        $totalDisalurkanBerasKg = (float) (clone $penyaluranDetailQuery)
            ->where('bentuk_salur', BentukZakatEnum::BERAS->value)
            ->sum('jumlah_beras_kg');

        $jumlahMuzakki = Muzakki::count();
        $jumlahMustahikLayak = Mustahik::where('status_kelayakan', StatusKelayakanEnum::LAYAK->value)->count();

        $agregasiAsnaf = PenyaluranDetail::query()
            ->select([
                'asnaf_id',
                DB::raw('COUNT(*) as jumlah_penerima'),
                DB::raw("SUM(CASE WHEN bentuk_salur = 'uang' THEN nominal_uang ELSE 0 END) as total_uang"),
                DB::raw("SUM(CASE WHEN bentuk_salur = 'beras' THEN jumlah_beras_kg ELSE 0 END) as total_beras_kg"),
            ])
            ->whereHas('penyaluran', function ($q) use ($periodeAktif): void {
                $q->where('status', StatusPenyaluranEnum::SELESAI->value)
                    ->when($periodeAktif, fn ($sub) => $sub->where('periode_id', $periodeAktif->id));
            })
            ->groupBy('asnaf_id')
            ->get()
            ->keyBy('asnaf_id');

        $rekapAsnaf = Asnaf::where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->map(function ($asnaf) use ($agregasiAsnaf) {
                $row = $agregasiAsnaf->get($asnaf->id);
                return [
                    'nama' => $asnaf->nama_asnaf,
                    'deskripsi' => $asnaf->deskripsi,
                    'jumlah_penerima' => $row ? (int) $row->jumlah_penerima : 0,
                    'total_uang' => $row ? (float) $row->total_uang : 0.0,
                    'total_beras_kg' => $row ? (float) $row->total_beras_kg : 0.0,
                ];
            });

        return view('public.beranda', compact(
            'periodeAktif',
            'nisabs',
            'informasi',
            'totalPenerimaanUang',
            'totalPenerimaanBerasKg',
            'totalTransaksiTerverifikasi',
            'totalDisalurkanUang',
            'totalDisalurkanBerasKg',
            'jumlahMuzakki',
            'jumlahMustahikLayak',
            'rekapAsnaf'
        ));
    }
}
