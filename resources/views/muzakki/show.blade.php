@extends('layouts.app')

@section('title', 'Detail Muzakki - Sistem Informasi Zakat')
@section('page_title', 'Detail Muzakki')

@section('content')
<div class="mb-4">
    <a href="{{ route('muzakki.index') }}" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Muzakki</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Profil Muzakki</h2>
        <dl class="space-y-3 text-sm">
            <div>
                <dt class="text-xs text-slate-500">Nama Lengkap</dt>
                <dd class="font-semibold text-slate-900">{{ $muzakki->nama_lengkap }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">NIK</dt>
                <dd class="font-medium text-slate-800">{{ $muzakki->nik ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Nomor HP</dt>
                <dd class="font-medium text-slate-800">{{ $muzakki->no_hp }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Email</dt>
                <dd class="font-medium text-slate-800">{{ $muzakki->email ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Alamat</dt>
                <dd class="text-slate-700">{{ $muzakki->alamat }}</dd>
            </div>
        </dl>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Riwayat Pembayaran Zakat</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Periode &amp; Metode</th>
                        <th class="px-5 py-3 font-semibold">Jumlah</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($muzakki->pembayaran as $trx)
                        <tr>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900">{{ $trx->kode_transaksi }}</div>
                                <div class="text-xs text-slate-500">{{ $trx->tanggal_bayar?->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                <div>{{ $trx->periode->nama_periode ?? '-' }}</div>
                                <div>{{ $trx->metodePembayaran->nama_metode ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-900">
                                @if ($trx->total_uang > 0)
                                    <div>Rp {{ number_format($trx->total_uang, 0, ',', '.') }}</div>
                                @endif
                                @if ($trx->total_beras_kg > 0)
                                    <div class="text-amber-700">{{ number_format($trx->total_beras_kg, 2, ',', '.') }} kg</div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <x-badge-status :status="$trx->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada riwayat transaksi pembayaran zakat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
