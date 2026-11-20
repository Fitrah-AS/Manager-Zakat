@extends('layouts.app')

@section('title', 'Pusat Laporan & BKU - Sistem Informasi Zakat')
@section('page_title', 'Laporan & Buku Kas Umum')

@section('content')
<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50/70 px-5 py-4 text-sm text-emerald-900">
    <p class="font-semibold">Seluruh laporan dihitung secara otomatis dari transaksi resmi (Pembayaran Terverifikasi, Penyaluran Selesai, Buku Kas Umum Rupiah, dan Mutasi Stok Beras Kg).</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach ($daftarLaporan as $kode => $info)
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-[#1B4D3E]/50 transition">
            <div>
                <div class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-[#1B4D3E] mb-3">
                    {{ str_replace('_', ' ', $kode) }}
                </div>
                <h2 class="text-base font-bold text-slate-900">{{ $info['judul'] }}</h2>
                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">{{ $info['deskripsi'] }}</p>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap items-center gap-2">
                <a href="{{ route('laporan.show', $kode) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-2 text-xs font-semibold text-white">
                    Buka Laporan &rarr;
                </a>
                <a href="{{ route('laporan.pdf', $kode) }}" class="inline-flex items-center rounded-lg border border-slate-200 hover:bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700">
                    PDF
                </a>
                <a href="{{ route('laporan.excel', $kode) }}" class="inline-flex items-center rounded-lg border border-emerald-200 bg-emerald-50/60 hover:bg-emerald-100 px-3 py-2 text-xs font-semibold text-emerald-800">
                    Excel
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
