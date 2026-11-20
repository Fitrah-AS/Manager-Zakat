@extends('layouts.app')

@section('title', 'Transaksi Pembayaran Zakat - Sistem Informasi Zakat')
@section('page_title', 'Daftar Transaksi Pembayaran Zakat')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="{{ route('pembayaran.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari kode transaksi / nama muzakki..."
                class="w-full sm:w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none"
            >
            <select name="periode_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Periode</option>
                @foreach ($periodes as $p)
                    <option value="{{ $p->id }}" @selected((string) $periodeId === (string) $p->id)>{{ $p->nama_periode }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi" @selected($status === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                <option value="terverifikasi" @selected($status === 'terverifikasi')>Terverifikasi</option>
                <option value="ditolak" @selected($status === 'ditolak')>Ditolak</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Filter
            </button>
        </form>

        <a
            href="{{ route('pembayaran.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white shadow-xs"
        >
            <span>+ Catat Pembayaran Zakat</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Muzakki</th>
                    <th class="px-5 py-3 font-semibold">Jenis &amp; Metode</th>
                    <th class="px-5 py-3 font-semibold">Nominal / Beras</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pembayarans as $trx)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('pembayaran.show', $trx) }}" class="font-bold text-[#1B4D3E] hover:underline">{{ $trx->kode_transaksi }}</a>
                            <div class="text-xs text-slate-500">{{ $trx->tanggal_bayar?->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900">{{ $trx->muzakki->nama_lengkap ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $trx->muzakki->no_hp ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">
                            <div class="font-semibold">{{ $trx->details->first()?->jenisZakat->nama_jenis ?? '-' }}</div>
                            <div class="text-slate-500">{{ $trx->metodePembayaran->nama_metode ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-bold text-slate-900">
                            @if ($trx->total_uang > 0)
                                <div>Rp {{ number_format($trx->total_uang, 0, ',', '.') }}</div>
                            @endif
                            @if ($trx->total_beras_kg > 0)
                                <div class="text-amber-700">{{ number_format($trx->total_beras_kg, 2, ',', '.') }} kg beras</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <x-badge-status :status="$trx->status" />
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <a href="{{ route('pembayaran.show', $trx) }}" class="inline-flex rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700">Detail</a>
                            <a href="{{ route('pembayaran.cetak', $trx) }}" target="_blank" class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-[#1B4D3E]">Cetak</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada transaksi pembayaran zakat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $pembayarans->links() }}</div>
</div>
@endsection
