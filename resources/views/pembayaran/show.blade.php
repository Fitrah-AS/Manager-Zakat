@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $pembayaran->kode_transaksi)
@section('page_title', 'Detail Pembayaran: ' . $pembayaran->kode_transaksi)

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <a href="{{ route('pembayaran.index') }}" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Pembayaran</a>

    <div class="flex flex-wrap items-center gap-2">
        @if ($pembayaran->status->value === 'menunggu_verifikasi')
            <a href="{{ route('verifikasi.show', $pembayaran) }}" class="rounded-lg bg-[#C9A227] hover:bg-[#B8921F] px-3.5 py-2 text-xs font-bold text-slate-950">
                Periksa &amp; Verifikasi
            </a>
        @endif
        <a href="{{ route('pembayaran.cetak', $pembayaran) }}" target="_blank" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700">
            Cetak Bukti Pembayaran
        </a>
        <a href="{{ route('pembayaran.pdf', $pembayaran) }}" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-2 text-xs font-semibold text-white">
            Download PDF
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 mb-4">
                <div>
                    <p class="text-xs text-slate-500">Kode Transaksi</p>
                    <h2 class="text-xl font-bold text-slate-900">{{ $pembayaran->kode_transaksi }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $pembayaran->tanggal_bayar?->format('d F Y, H:i') }} WIB • {{ $pembayaran->periode->nama_periode ?? '-' }}</p>
                </div>
                <x-badge-status :status="$pembayaran->status" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-slate-500">Muzakki</p>
                    <p class="font-semibold text-slate-900">{{ $pembayaran->muzakki->nama_lengkap ?? '-' }}</p>
                    <p class="text-xs text-slate-600">{{ $pembayaran->muzakki->no_hp ?? '-' }}</p>
                    <p class="text-xs text-slate-500">{{ $pembayaran->muzakki->alamat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Metode &amp; Petugas</p>
                    <p class="font-semibold text-slate-900">{{ $pembayaran->metodePembayaran->nama_metode ?? '-' }}</p>
                    <p class="text-xs text-slate-600">Diinput oleh: {{ $pembayaran->petugasInput->name ?? '-' }}</p>
                    @if ($pembayaran->verifikator)
                        <p class="text-xs text-emerald-700">Diverifikasi oleh: {{ $pembayaran->verifikator->name }} ({{ $pembayaran->verified_at?->format('d/m/Y H:i') }})</p>
                    @endif
                </div>
            </div>

            @if ($pembayaran->alasan_penolakan)
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-800">
                    <span class="font-bold">Alasan Penolakan:</span> {{ $pembayaran->alasan_penolakan }}
                </div>
            @endif
        </div>

        {{-- Rincian Item --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Rincian Pembayaran Zakat</h3>

            @foreach ($pembayaran->details as $detail)
                <div class="rounded-lg border border-slate-200 p-4 bg-slate-50/50 space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-bold text-slate-900">{{ $detail->jenisZakat->nama_jenis ?? '-' }}</p>
                            <p class="text-xs text-slate-500">Bentuk bayar: {{ $detail->bentuk_bayar->label() }}</p>
                        </div>
                        <div class="text-right">
                            @if ($detail->nominal_uang > 0)
                                <p class="text-base font-bold text-[#1B4D3E]">Rp {{ number_format($detail->nominal_uang, 0, ',', '.') }}</p>
                            @endif
                            @if ($detail->jumlah_beras_kg > 0)
                                <p class="text-base font-bold text-amber-700">{{ number_format($detail->jumlah_beras_kg, 2, ',', '.') }} kg beras</p>
                            @endif
                        </div>
                    </div>

                    @if ($detail->jiwa->isNotEmpty())
                        <div>
                            <p class="text-xs font-semibold text-slate-700 mb-1.5">Daftar Jiwa ({{ $detail->jumlah_jiwa }} Orang):</p>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-xs">
                                @foreach ($detail->jiwa as $idx => $jiwa)
                                    <li class="rounded bg-white border border-slate-200 px-3 py-1.5">
                                        {{ $idx + 1 }}. <span class="font-semibold">{{ $jiwa->nama_anggota }}</span>
                                        @if ($jiwa->hubungan_keluarga)
                                            <span class="text-slate-500">({{ $jiwa->hubungan_keluarga }})</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($detail->perhitunganZakatMal)
                        @php $calc = $detail->perhitunganZakatMal; @endphp
                        <div class="rounded-lg bg-white border border-slate-200 p-3 text-xs space-y-1">
                            <p class="font-bold text-[#1B4D3E]">Rincian Perhitungan Zakat Mal ({{ strtoupper(str_replace('_', ' ', $calc->jenis_harta)) }})</p>
                            <p>Nilai Harta Kotor: <span class="font-semibold">Rp {{ number_format($calc->nilai_harta_kotor, 0, ',', '.') }}</span></p>
                            <p>Pengurang / Hutang: <span class="font-semibold">Rp {{ number_format($calc->hutang_jatuh_tempo, 0, ',', '.') }}</span></p>
                            <p>Harta Bersih: <span class="font-semibold">Rp {{ number_format($calc->harta_bersih, 0, ',', '.') }}</span></p>
                            <p class="text-slate-500">{{ $calc->catatan_perhitungan }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bukti Transfer --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4 h-fit">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Bukti Transfer / Dokumen</h3>
        @if ($pembayaran->bukti_transfer)
            @php $isPdf = str_ends_with(strtolower($pembayaran->bukti_transfer), '.pdf'); @endphp
            @if ($isPdf)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-center text-xs text-slate-600">
                    Dokumen bukti berupa file PDF.
                </div>
            @else
                <img src="{{ route('pembayaran.bukti', $pembayaran) }}" alt="Bukti Transfer" class="w-full rounded-lg border border-slate-200 object-contain max-h-80 bg-slate-50">
            @endif
            <a href="{{ route('pembayaran.bukti', $pembayaran) }}" target="_blank" class="block w-full text-center rounded-lg border border-[#1B4D3E] text-[#1B4D3E] hover:bg-emerald-50 px-4 py-2 text-xs font-semibold">
                Buka File Bukti Asli
            </a>
        @else
            <p class="text-xs text-slate-500">Tidak ada lampiran bukti transfer (pembayaran tunai loket).</p>
        @endif
    </div>
</div>
@endsection
