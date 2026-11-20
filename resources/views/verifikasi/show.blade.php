@extends('layouts.app')

@section('title', 'Periksa Verifikasi ' . $pembayaran->kode_transaksi)
@section('page_title', 'Verifikasi Transaksi: ' . $pembayaran->kode_transaksi)

@section('content')
<div class="mb-4">
    <a href="{{ route('verifikasi.index') }}" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Verifikasi</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $pembayaran->kode_transaksi }}</h2>
                    <p class="text-xs text-slate-500">{{ $pembayaran->tanggal_bayar?->format('d F Y, H:i') }} WIB • {{ $pembayaran->periode->nama_periode ?? '-' }}</p>
                </div>
                <x-badge-status :status="$pembayaran->status" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-5">
                <div>
                    <p class="text-xs text-slate-500">Muzakki</p>
                    <p class="font-semibold text-slate-900">{{ $pembayaran->muzakki->nama_lengkap ?? '-' }}</p>
                    <p class="text-xs text-slate-600">{{ $pembayaran->muzakki->no_hp ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Metode &amp; Petugas Input</p>
                    <p class="font-semibold text-slate-900">{{ $pembayaran->metodePembayaran->nama_metode ?? '-' }}</p>
                    <p class="text-xs text-slate-600">Diinput: {{ $pembayaran->petugasInput->name ?? '-' }}</p>
                </div>
            </div>

            @foreach ($pembayaran->details as $detail)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-bold text-slate-900">{{ $detail->jenisZakat->nama_jenis ?? '-' }}</p>
                        @if ($detail->jumlah_jiwa)
                            <p class="text-xs text-slate-600">{{ $detail->jumlah_jiwa }} jiwa: {{ $detail->jiwa->pluck('nama_anggota')->implode(', ') }}</p>
                        @endif
                    </div>
                    <div class="text-right font-bold text-base">
                        @if ($detail->nominal_uang > 0)
                            <span class="text-[#1B4D3E]">Rp {{ number_format($detail->nominal_uang, 0, ',', '.') }}</span>
                        @endif
                        @if ($detail->jumlah_beras_kg > 0)
                            <span class="text-amber-700">{{ number_format($detail->jumlah_beras_kg, 2, ',', '.') }} kg beras</span>
                        @endif
                    </div>
                </div>
            @endforeach

            @if ($pembayaran->status->value === 'menunggu_verifikasi')
                <div class="mt-6 pt-5 border-t border-slate-200 flex flex-wrap items-center gap-3">
                    <form method="POST" action="{{ route('verifikasi.terima', $pembayaran) }}" data-loading-form>
                        @csrf
                        @method('PATCH')
                        <button type="submit" data-loading-text="Memverifikasi..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-5 py-2.5 text-xs font-bold text-white shadow cursor-pointer">
                            <span data-submit-label>Terima &amp; Verifikasi Pembayaran</span>
                        </button>
                    </form>

                    <button type="button" data-modal-open="modal-tolak-pembayaran" class="rounded-lg border border-red-300 bg-red-50 hover:bg-red-100 px-5 py-2.5 text-xs font-bold text-red-700 cursor-pointer">
                        Tolak Pembayaran
                    </button>
                </div>
            @else
                <div class="mt-5 rounded-lg bg-slate-50 border border-slate-200 p-4 text-xs text-slate-700">
                    <p><span class="font-semibold">Diverifikasi/Diproses oleh:</span> {{ $pembayaran->verifikator->name ?? '-' }} ({{ $pembayaran->verified_at?->format('d/m/Y H:i') }} WIB)</p>
                    @if ($pembayaran->alasan_penolakan)
                        <p class="mt-1 text-red-700"><span class="font-semibold">Alasan Penolakan:</span> {{ $pembayaran->alasan_penolakan }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4 h-fit">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Bukti Transfer</h3>
        @if ($pembayaran->bukti_transfer)
            @if (str_ends_with(strtolower($pembayaran->bukti_transfer), '.pdf'))
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-center text-xs text-slate-600">Dokumen PDF terlampir.</div>
            @else
                <img src="{{ route('pembayaran.bukti', $pembayaran) }}" alt="Bukti Transfer" class="w-full rounded-lg border border-slate-200 object-contain max-h-96 bg-slate-50">
            @endif
            <a href="{{ route('pembayaran.bukti', $pembayaran) }}" target="_blank" class="block w-full text-center rounded-lg border border-[#1B4D3E] text-[#1B4D3E] hover:bg-emerald-50 px-4 py-2 text-xs font-semibold">
                Buka Bukti Ukuran Penuh
            </a>
        @else
            <p class="text-xs text-slate-500">Tidak ada file bukti transfer yang dilampirkan.</p>
        @endif
    </div>
</div>

{{-- Modal Tolak Pembayaran --}}
<div id="modal-tolak-pembayaran" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-red-600 text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tolak Pembayaran</h3>
            <button type="button" data-modal-close class="text-red-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="{{ route('verifikasi.tolak', $pembayaran) }}" data-loading-form class="p-6 space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Penolakan *</label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Contoh: Nominal transfer tidak sesuai atau bukti transfer tidak terbaca." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menolak..." class="rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                    <span data-submit-label>Konfirmasi Tolak</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
