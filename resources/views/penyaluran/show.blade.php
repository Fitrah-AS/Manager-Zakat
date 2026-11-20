@extends('layouts.app')

@section('title', 'Detail Penyaluran ' . $penyaluran->kode_penyaluran)
@section('page_title', 'Detail Penyaluran: ' . $penyaluran->kode_penyaluran)

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <a href="{{ route('penyaluran.index') }}" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Penyaluran</a>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('penyaluran.cetak', $penyaluran) }}" target="_blank" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700">
            Cetak Bukti Penyaluran
        </a>
        <a href="{{ route('penyaluran.pdf', $penyaluran) }}" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-2 text-xs font-semibold text-white">
            Download PDF
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs mb-6">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 mb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">{{ $penyaluran->kode_penyaluran }}</h2>
            <p class="text-xs text-slate-500">
                Tanggal: {{ $penyaluran->tanggal_penyaluran?->format('d F Y, H:i') }} WIB •
                Periode: {{ $penyaluran->periode->nama_periode ?? '-' }} •
                Petugas: {{ $penyaluran->petugas->name ?? '-' }}
            </p>
            @if ($penyaluran->keterangan)
                <p class="text-xs text-slate-600 mt-1">{{ $penyaluran->keterangan }}</p>
            @endif
        </div>
        <x-badge-status :status="$penyaluran->status" />
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 font-semibold">Mustahik</th>
                    <th class="px-4 py-3 font-semibold">Asnaf</th>
                    <th class="px-4 py-3 font-semibold">Jenis Zakat</th>
                    <th class="px-4 py-3 font-semibold">Bentuk</th>
                    <th class="px-4 py-3 font-semibold text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($penyaluran->details as $d)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900">{{ $d->mustahik->nama_lengkap ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $d->mustahik->alamat ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs font-semibold text-slate-700">{{ $d->asnaf->nama_asnaf ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $d->jenisZakat->nama_jenis ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs">{{ $d->bentuk_salur->label() }}</td>
                        <td class="px-4 py-3 text-right font-bold">
                            @if ($d->bentuk_salur->value === 'uang')
                                <span class="text-[#1B4D3E]">Rp {{ number_format($d->nominal_uang, 0, ',', '.') }}</span>
                            @else
                                <span class="text-amber-700">{{ number_format($d->jumlah_beras_kg, 2, ',', '.') }} kg</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($penyaluran->status->value === 'direncanakan')
        <div class="mt-6 pt-5 border-t border-slate-200 flex flex-wrap items-center gap-3">
            <form method="POST" action="{{ route('penyaluran.selesai', $penyaluran) }}" data-loading-form>
                @csrf
                @method('PATCH')
                <button type="submit" data-loading-text="Memproses..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-5 py-2.5 text-xs font-bold text-white shadow cursor-pointer">
                    <span data-submit-label>Selesaikan Penyaluran (Potong Kas / Stok Beras)</span>
                </button>
            </form>

            <form method="POST" action="{{ route('penyaluran.batal', $penyaluran) }}" data-loading-form>
                @csrf
                @method('PATCH')
                <button type="submit" data-loading-text="Membatalkan..." class="rounded-lg border border-red-300 bg-red-50 hover:bg-red-100 px-4 py-2.5 text-xs font-bold text-red-700 cursor-pointer">
                    <span data-submit-label>Batalkan Rencana</span>
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
