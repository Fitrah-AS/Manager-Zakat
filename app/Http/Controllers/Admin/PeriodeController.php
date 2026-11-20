<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PeriodeRequest;
use App\Models\Periode;
use App\Models\TarifZakatFitrah;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeriodeController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    public function index(): View
    {
        $periodes = Periode::with('tarifFitrah')
            ->latest('tanggal_mulai')
            ->paginate(12);

        return view('admin.periode.index', compact('periodes'));
    }

    public function store(PeriodeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $isActive = $request->boolean('is_active');

        $periode = DB::transaction(function () use ($data, $isActive): Periode {
            if ($isActive) {
                Periode::where('is_active', true)->update(['is_active' => false]);
            }

            $periode = Periode::create([
                'nama_periode' => $data['nama_periode'],
                'tahun_masehi' => $data['tahun_masehi'],
                'tahun_hijriah' => $data['tahun_hijriah'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'batas_akhir_fitrah' => $data['batas_akhir_fitrah'] ?? null,
                'is_active' => $isActive,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            TarifZakatFitrah::create([
                'periode_id' => $periode->id,
                'beras_per_jiwa_kg' => $data['beras_per_jiwa_kg'],
                'nominal_uang' => $data['nominal_uang'],
                'keterangan' => $data['keterangan_tarif'] ?? null,
            ]);

            return $periode;
        });

        $this->auditLogService->record(
            aksi: 'create',
            modul: 'periode',
            deskripsi: "Menambahkan periode zakat {$periode->nama_periode} ({$periode->tahun_hijriah}) beserta tarif fitrahnya.",
            model: $periode,
            dataBaru: $periode->load('tarifFitrah')->toArray()
        );

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode {$periode->nama_periode} berhasil ditambahkan.");
    }

    public function update(PeriodeRequest $request, Periode $periode): RedirectResponse
    {
        $data = $request->validated();
        $isActive = $request->boolean('is_active');
        $dataLama = $periode->load('tarifFitrah')->toArray();

        DB::transaction(function () use ($periode, $data, $isActive): void {
            if ($isActive) {
                Periode::where('id', '!=', $periode->id)->update(['is_active' => false]);
            }

            $periode->update([
                'nama_periode' => $data['nama_periode'],
                'tahun_masehi' => $data['tahun_masehi'],
                'tahun_hijriah' => $data['tahun_hijriah'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'batas_akhir_fitrah' => $data['batas_akhir_fitrah'] ?? null,
                'is_active' => $isActive,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            $periode->tarifFitrah()->updateOrCreate(
                ['periode_id' => $periode->id],
                [
                    'beras_per_jiwa_kg' => $data['beras_per_jiwa_kg'],
                    'nominal_uang' => $data['nominal_uang'],
                    'keterangan' => $data['keterangan_tarif'] ?? null,
                ]
            );
        });

        $this->auditLogService->record(
            aksi: 'update',
            modul: 'periode',
            deskripsi: "Memperbarui periode zakat {$periode->nama_periode} dan tarif zakat fitrah.",
            model: $periode,
            dataLama: $dataLama,
            dataBaru: $periode->fresh('tarifFitrah')->toArray()
        );

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode {$periode->nama_periode} berhasil diperbarui.");
    }

    public function activate(Periode $periode): RedirectResponse
    {
        DB::transaction(function () use ($periode): void {
            Periode::where('is_active', true)->update(['is_active' => false]);
            $periode->update(['is_active' => true]);
        });

        $this->auditLogService->record(
            aksi: 'activate',
            modul: 'periode',
            deskripsi: "Mengaktifkan periode {$periode->nama_periode} ({$periode->tahun_hijriah}).",
            model: $periode
        );

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode {$periode->nama_periode} kini menjadi periode aktif.");
    }

    public function destroy(Periode $periode): RedirectResponse
    {
        if ($periode->sudahDigunakan()) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Periode tidak dapat dihapus karena sudah digunakan pada transaksi zakat.');
        }

        if ($periode->is_active) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Periode yang sedang aktif tidak dapat dihapus. Aktifkan periode lain terlebih dahulu.');
        }

        $dataLama = $periode->toArray();
        $nama = $periode->nama_periode;

        DB::transaction(function () use ($periode): void {
            $periode->tarifFitrah()->delete();
            $periode->delete();
        });

        $this->auditLogService->record(
            aksi: 'delete',
            modul: 'periode',
            deskripsi: "Menghapus periode zakat {$nama}.",
            dataLama: $dataLama
        );

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode {$nama} berhasil dihapus.");
    }
}
