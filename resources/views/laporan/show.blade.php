@extends('layouts.app')

@section('title', $laporan['judul'] . ' - Sistem Informasi Zakat')
@section('page_title', $laporan['judul'])

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Pusat Laporan</a>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('laporan.cetak', array_merge(['jenis' => $laporan['jenis']], $filters)) }}" target="_blank" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700">
            Cetak Halaman
        </a>
        <a href="{{ route('laporan.pdf', array_merge(['jenis' => $laporan['jenis']], $filters)) }}" class="rounded-lg bg-red-600 hover:bg-red-700 px-3.5 py-2 text-xs font-semibold text-white">
            Unduh PDF
        </a>
        <a href="{{ route('laporan.excel', array_merge(['jenis' => $laporan['jenis']], $filters)) }}" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-2 text-xs font-semibold text-white">
            Ekspor Excel (.xlsx)
        </a>
    </div>
</div>

{{-- Filter Form --}}
<form method="GET" action="{{ route('laporan.show', $laporan['jenis']) }}" class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Periode</label>
            <select name="periode_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
                <option value="">Semua Periode</option>
                @foreach ($periodes as $p)
                    <option value="{{ $p->id }}" @selected(($filters['periode_id'] ?? '') == $p->id)>{{ $p->nama_periode }}</option>
                @endforeach
            </select>
        </div>

        @if (! in_array($laporan['jenis'], ['muzakki', 'mustahik']))
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
            </div>
        @endif

        @if (in_array($laporan['jenis'], ['penyaluran_asnaf', 'mustahik']))
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Asnaf</label>
                <select name="asnaf_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
                    <option value="">Semua Asnaf</option>
                    @foreach ($asnafs as $a)
                        <option value="{{ $a->id }}" @selected(($filters['asnaf_id'] ?? '') == $a->id)>{{ $a->nama_asnaf }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Pencarian</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama / Kode / Keterangan..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2 text-xs font-semibold text-white cursor-pointer">Terapkan</button>
            <a href="{{ route('laporan.show', $laporan['jenis']) }}" class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
        </div>
    </div>
</form>

{{-- Ringkasan Angka --}}
@if (! empty($laporan['ringkasan']))
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @foreach ($laporan['ringkasan'] as $item)
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                <p class="text-xs text-slate-500">{{ $item['label'] }}</p>
                <p class="text-lg font-bold text-[#1B4D3E] mt-1">{{ $item['tampil'] }}</p>
            </div>
        @endforeach
    </div>
@endif

{{-- Rekapitulasi Per Asnaf (khusus laporan penyaluran_asnaf) --}}
@if (! empty($laporan['rekapAsnaf']))
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden mb-6">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h3 class="text-sm font-bold text-slate-900">Rekapitulasi Penyaluran per Golongan Asnaf</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-2.5 font-semibold">Golongan Asnaf</th>
                        <th class="px-4 py-2.5 font-semibold text-center">Jumlah Penerima</th>
                        <th class="px-4 py-2.5 font-semibold text-right">Total Uang (Rp)</th>
                        <th class="px-4 py-2.5 font-semibold text-right">Total Beras (Kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($laporan['rekapAsnaf'] as $rekap)
                        <tr>
                            <td class="px-4 py-2.5 font-semibold text-slate-800">{{ $rekap['nama_asnaf'] }}</td>
                            <td class="px-4 py-2.5 text-center">{{ $rekap['jumlah_penerima'] }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold text-[#1B4D3E]">Rp {{ number_format($rekap['total_uang'], 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold text-amber-700">{{ number_format($rekap['total_beras_kg'], 2, ',', '.') }} kg</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- Tabel Data Utama --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-900">{{ $laporan['judul'] }}</h3>
            <p class="text-xs text-slate-500">Periode: {{ $laporan['periodeLabel'] }}</p>
        </div>
        <span class="text-xs font-semibold text-slate-500">{{ count($laporan['rows']) }} baris data</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#1B4D3E] text-white uppercase">
                <tr>
                    @foreach ($laporan['kolom'] as $kolom)
                        <th class="px-4 py-3 font-semibold whitespace-nowrap">{{ $kolom }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($laporan['rows'] as $row)
                    <tr class="hover:bg-slate-50">
                        @foreach ($row as $cell)
                            <td class="px-4 py-2.5 text-slate-700">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($laporan['kolom']) }}" class="px-5 py-10 text-center text-sm text-slate-400">
                            Belum ada data yang sesuai dengan filter laporan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
