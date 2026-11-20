@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Sistem Informasi Zakat')
@section('page_title', 'Verifikasi Pembayaran Zakat')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-3.5 text-sm text-amber-900">
    <div>
        <span class="font-bold">{{ $jumlahMenunggu }} transaksi</span> menunggu verifikasi oleh petugas/amil yang bertugas.
    </div>
    <div class="flex flex-wrap gap-2 text-xs font-semibold">
        <a href="{{ route('verifikasi.index', ['status' => 'menunggu_verifikasi']) }}" class="px-3 py-1.5 rounded-lg {{ $status === 'menunggu_verifikasi' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Menunggu</a>
        <a href="{{ route('verifikasi.index', ['status' => 'terverifikasi']) }}" class="px-3 py-1.5 rounded-lg {{ $status === 'terverifikasi' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Terverifikasi</a>
        <a href="{{ route('verifikasi.index', ['status' => 'ditolak']) }}" class="px-3 py-1.5 rounded-lg {{ $status === 'ditolak' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Ditolak</a>
        <a href="{{ route('verifikasi.index', ['status' => 'semua']) }}" class="px-3 py-1.5 rounded-lg {{ $status === 'semua' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200' }}">Semua</a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Muzakki</th>
                    <th class="px-5 py-3 font-semibold">Metode &amp; Bukti</th>
                    <th class="px-5 py-3 font-semibold">Jumlah</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pembayarans as $trx)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('verifikasi.show', $trx) }}" class="font-bold text-[#1B4D3E] hover:underline">{{ $trx->kode_transaksi }}</a>
                            <div class="text-xs text-slate-500">{{ $trx->tanggal_bayar?->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900">{{ $trx->muzakki->nama_lengkap ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $trx->details->first()?->jenisZakat->nama_jenis ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            <div class="font-medium text-slate-800">{{ $trx->metodePembayaran->nama_metode ?? '-' }}</div>
                            @if ($trx->bukti_transfer)
                                <a href="{{ route('pembayaran.bukti', $trx) }}" target="_blank" class="text-[#1B4D3E] font-semibold hover:underline">Lihat Bukti &rarr;</a>
                            @else
                                <span class="text-slate-400">Tanpa lampiran</span>
                            @endif
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
                            @if ($trx->verifikator)
                                <div class="text-[11px] text-slate-400 mt-1">Oleh: {{ $trx->verifikator->name }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('verifikasi.show', $trx) }}" class="inline-flex rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-1.5 text-xs font-semibold text-white">
                                Periksa &amp; Verifikasi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Tidak ada transaksi pada filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $pembayarans->links() }}</div>
</div>
@endsection
