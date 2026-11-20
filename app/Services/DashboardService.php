<?php

namespace App\Services;

use App\Enums\BentukZakatEnum;
use App\Enums\StatusKelayakanEnum;
use App\Enums\StatusPembayaranEnum;
use App\Enums\StatusPenyaluranEnum;
use App\Models\AktivitasLog;
use App\Models\Asnaf;
use App\Models\KasTransaksi;
use App\Models\Mustahik;
use App\Models\MutasiStokBeras;
use App\Models\Muzakki;
use App\Models\Pembayaran;
use App\Models\Penyaluran;
use App\Models\PenyaluranDetail;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getSummaryData(): array
    {
        $periodeAktif = Periode::aktif()->with('tarifFitrah')->first();

        $jumlahMuzakki = Muzakki::count();
        $jumlahMustahik = Mustahik::count();
        $jumlahMustahikLayak = Mustahik::where('status_kelayakan', StatusKelayakanEnum::LAYAK->value)->count();

        $pembayaranTerverifikasiQuery = Pembayaran::query()
            ->where('status', StatusPembayaranEnum::TERVERIFIKASI->value);

        if ($periodeAktif) {
            $pembayaranTerverifikasiQuery->where('periode_id', $periodeAktif->id);
        }

        $totalPenerimaanUang = (float) (clone $pembayaranTerverifikasiQuery)->sum('total_uang');
        $totalPenerimaanBerasKg = (float) (clone $pembayaranTerverifikasiQuery)->sum('total_beras_kg');

        $menungguVerifikasiCount = Pembayaran::where('status', StatusPembayaranEnum::MENUNGGU_VERIFIKASI->value)->count();

        $penyaluranDetailSelesaiQuery = PenyaluranDetail::query()
            ->whereHas('penyaluran', function ($query) use ($periodeAktif): void {
                $query->where('status', StatusPenyaluranEnum::SELESAI->value);
                if ($periodeAktif) {
                    $query->where('periode_id', $periodeAktif->id);
                }
            });

        $totalDisalurkanUang = (float) (clone $penyaluranDetailSelesaiQuery)
            ->where('bentuk_salur', BentukZakatEnum::UANG->value)
            ->sum('nominal_uang');

        $totalDisalurkanBerasKg = (float) (clone $penyaluranDetailSelesaiQuery)
            ->where('bentuk_salur', BentukZakatEnum::BERAS->value)
            ->sum('jumlah_beras_kg');

        // Saldo kas uang & stok beras saat ini dihitung dari buku mutasi masing-masing agar tidak bercampur satuan
        $saldoKasUang = (float) (KasTransaksi::latest('id')->value('saldo_setelah') ?? 0);
        $stokBerasSaatIniKg = (float) (MutasiStokBeras::latest('id')->value('saldo_sesudah_kg') ?? 0);

        $transaksiTerbaru = Pembayaran::with(['muzakki', 'metodePembayaran', 'details.jenisZakat'])
            ->latest('tanggal_bayar')
            ->limit(6)
            ->get();

        $aktivitasTerbaru = AktivitasLog::with('user')
            ->latest('id')
            ->limit(8)
            ->get();

        return [
            'periodeAktif' => $periodeAktif,
            'jumlahMuzakki' => $jumlahMuzakki,
            'jumlahMustahik' => $jumlahMustahik,
            'jumlahMustahikLayak' => $jumlahMustahikLayak,
            'totalPenerimaanUang' => $totalPenerimaanUang,
            'totalPenerimaanBerasKg' => $totalPenerimaanBerasKg,
            'totalDisalurkanUang' => $totalDisalurkanUang,
            'totalDisalurkanBerasKg' => $totalDisalurkanBerasKg,
            'saldoKasUang' => $saldoKasUang,
            'stokBerasSaatIniKg' => $stokBerasSaatIniKg,
            'menungguVerifikasiCount' => $menungguVerifikasiCount,
            'transaksiTerbaru' => $transaksiTerbaru,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'grafikPenerimaan' => $this->getGrafikPenerimaan($periodeAktif),
            'grafikPenyaluranAsnaf' => $this->getGrafikPenyaluranPerAsnaf($periodeAktif),
        ];
    }

    private function getGrafikPenerimaan(?Periode $periodeAktif): array
    {
        $rows = Pembayaran::query()
            ->select([
                DB::raw("DATE_FORMAT(tanggal_bayar, '%Y-%m') as bulan"),
                DB::raw('SUM(total_uang) as total_uang'),
                DB::raw('SUM(total_beras_kg) as total_beras_kg'),
            ])
            ->where('status', StatusPembayaranEnum::TERVERIFIKASI->value)
            ->when($periodeAktif, fn ($q) => $q->where('periode_id', $periodeAktif->id))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels = [];
        $uangSeries = [];
        $berasSeries = [];

        foreach ($rows as $row) {
            $labels[] = Carbon::createFromFormat('Y-m', $row->bulan)->translatedFormat('M Y');
            $uangSeries[] = (float) $row->total_uang;
            $berasSeries[] = (float) $row->total_beras_kg;
        }

        return [
            'labels' => $labels,
            'uang' => $uangSeries,
            'beras' => $berasSeries,
        ];
    }

    private function getGrafikPenyaluranPerAsnaf(?Periode $periodeAktif): array
    {
        $asnafs = Asnaf::orderBy('urutan')->get(['id', 'nama_asnaf']);

        $agregasi = PenyaluranDetail::query()
            ->select([
                'asnaf_id',
                DB::raw("SUM(CASE WHEN bentuk_salur = 'uang' THEN nominal_uang ELSE 0 END) as total_uang"),
                DB::raw("SUM(CASE WHEN bentuk_salur = 'beras' THEN jumlah_beras_kg ELSE 0 END) as total_beras_kg"),
            ])
            ->whereHas('penyaluran', function ($query) use ($periodeAktif): void {
                $query->where('status', StatusPenyaluranEnum::SELESAI->value);
                if ($periodeAktif) {
                    $query->where('periode_id', $periodeAktif->id);
                }
            })
            ->groupBy('asnaf_id')
            ->get()
            ->keyBy('asnaf_id');

        $labels = [];
        $uangSeries = [];
        $berasSeries = [];

        foreach ($asnafs as $asnaf) {
            $item = $agregasi->get($asnaf->id);
            $uang = $item ? (float) $item->total_uang : 0.0;
            $beras = $item ? (float) $item->total_beras_kg : 0.0;

            if ($uang > 0 || $beras > 0) {
                $labels[] = $asnaf->nama_asnaf;
                $uangSeries[] = $uang;
                $berasSeries[] = $beras;
            }
        }

        return [
            'labels' => $labels,
            'uang' => $uangSeries,
            'beras' => $berasSeries,
        ];
    }
}
