@extends('layouts.app')

@section('title', 'Manajemen Stok Beras - Sistem Informasi Zakat')
@section('page_title', 'Manajemen Stok Beras (Satuan Kilogram)')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <x-stat-card
        label="Stok Beras Tersedia Saat Ini"
        :value="number_format($stokSaatIniKg, 2, ',', '.') . ' kg'"
        sublabel="Saldo fisik aktual dalam kilogram"
        accent="gold"
    />
    <x-stat-card
        label="Total Beras Masuk"
        :value="number_format($totalMasukKg, 2, ',', '.') . ' kg'"
        sublabel="Dari zakat terverifikasi &amp; input manual"
        accent="emerald"
    />
    <x-stat-card
        label="Total Beras Keluar"
        :value="number_format($totalKeluarKg, 2, ',', '.') . ' kg'"
        sublabel="Dari penyaluran zakat berstatus selesai"
        accent="teal"
    />
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="{{ route('stok-beras.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <select name="periode_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Periode</option>
                @foreach ($periodes as $p)
                    <option value="{{ $p->id }}" @selected((string) $periodeId === (string) $p->id)>{{ $p->nama_periode }}</option>
                @endforeach
            </select>
            <select name="tipe" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Mutasi</option>
                <option value="masuk" @selected($tipe === 'masuk')>Masuk</option>
                <option value="keluar" @selected($tipe === 'keluar')>Keluar</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Filter
            </button>
        </form>

        <button type="button" data-modal-open="modal-tambah-beras" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white cursor-pointer">
            + Catat Beras Masuk Manual
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Tanggal Mutasi</th>
                    <th class="px-5 py-3 font-semibold">Tipe</th>
                    <th class="px-5 py-3 font-semibold">Jumlah (Kg)</th>
                    <th class="px-5 py-3 font-semibold">Saldo Sebelum &rarr; Sesudah</th>
                    <th class="px-5 py-3 font-semibold">Keterangan &amp; Petugas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($mutasis as $m)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5 text-xs text-slate-700 whitespace-nowrap">
                            <div>{{ $m->tanggal_mutasi?->format('d/m/Y H:i') }}</div>
                            <div class="text-slate-400">{{ $m->periode->tahun_hijriah ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $m->tipe === 'masuk' ? 'bg-emerald-100 text-[#0F5132]' : 'bg-amber-100 text-amber-900' }}">
                                {{ ucfirst($m->tipe) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-sm font-bold {{ $m->tipe === 'masuk' ? 'text-emerald-700' : 'text-red-700' }}">
                            {{ $m->tipe === 'masuk' ? '+' : '-' }}{{ number_format($m->jumlah_kg, 2, ',', '.') }} kg
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">
                            {{ number_format($m->saldo_sebelum_kg, 2, ',', '.') }} kg &rarr;
                            <span class="font-bold text-slate-900">{{ number_format($m->saldo_sesudah_kg, 2, ',', '.') }} kg</span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            <div>{{ $m->keterangan }}</div>
                            <div class="text-slate-400 mt-0.5">Oleh: {{ $m->createdBy->name ?? '-' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada riwayat mutasi stok beras.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $mutasis->links() }}</div>
</div>

{{-- Modal Tambah Stok Beras Manual --}}
<div id="modal-tambah-beras" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Catat Pemasukan Beras Manual</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="{{ route('stok-beras.store') }}" data-loading-form class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ now()->format('Y-m-d') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Beras (Kg)</label>
                    <input type="number" step="0.01" min="0.1" name="jumlah_kg" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Sumber Pemasukan</label>
                <select name="sumber" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="infak_beras">Infak / Sedekah Beras</option>
                    <option value="penyesuaian">Penyesuaian Stok Fisik</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" required placeholder="Contoh: Infak beras dari jamaah masjid / hasil stok opname" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                    <span data-submit-label>Simpan Stok Beras</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
