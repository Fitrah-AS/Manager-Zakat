@extends('layouts.app')

@section('title', 'Penyaluran Zakat - Sistem Informasi Zakat')
@section('page_title', 'Daftar Penyaluran Zakat')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="{{ route('penyaluran.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <select name="periode_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Periode</option>
                @foreach ($periodes as $p)
                    <option value="{{ $p->id }}" @selected((string) $periodeId === (string) $p->id)>{{ $p->nama_periode }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="direncanakan" @selected($status === 'direncanakan')>Direncanakan</option>
                <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Filter
            </button>
        </form>

        <a href="{{ route('penyaluran.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white shadow-xs">
            + Buat Penyaluran Zakat
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Petugas</th>
                    <th class="px-5 py-3 font-semibold">Penerima &amp; Total</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($penyalurans as $item)
                    @php
                        $totalUang = $item->details->where('bentuk_salur.value', 'uang')->sum('nominal_uang');
                        $totalBeras = $item->details->where('bentuk_salur.value', 'beras')->sum('jumlah_beras_kg');
                    @endphp
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('penyaluran.show', $item) }}" class="font-bold text-[#1B4D3E] hover:underline">{{ $item->kode_penyaluran }}</a>
                            <div class="text-xs text-slate-500">{{ $item->tanggal_penyaluran?->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">
                            <div class="font-semibold">{{ $item->petugas->name ?? '-' }}</div>
                            <div class="text-slate-400">{{ $item->periode->tahun_hijriah ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            <div class="text-slate-500">{{ $item->details_count }} mustahik</div>
                            @if ($totalUang > 0)
                                <div class="font-bold text-slate-900">Rp {{ number_format($totalUang, 0, ',', '.') }}</div>
                            @endif
                            @if ($totalBeras > 0)
                                <div class="font-bold text-amber-700">{{ number_format($totalBeras, 2, ',', '.') }} kg beras</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <x-badge-status :status="$item->status" />
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <a href="{{ route('penyaluran.show', $item) }}" class="inline-flex rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700">Detail</a>
                            <a href="{{ route('penyaluran.cetak', $item) }}" target="_blank" class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-[#1B4D3E]">Cetak</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada data penyaluran zakat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $penyalurans->links() }}</div>
</div>
@endsection
