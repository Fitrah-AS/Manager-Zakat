<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Zakat - {{ $pembayaran->kode_transaksi }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 p-6">
    <div class="max-w-2xl mx-auto mb-4 flex justify-end gap-2 no-print">
        <button onclick="window.print()" class="rounded-lg bg-[#1B4D3E] text-white px-4 py-2 text-xs font-bold cursor-pointer">
            Cetak Halaman Ini
        </button>
    </div>

    <div class="max-w-2xl mx-auto bg-white rounded-xl border border-slate-300 p-8 shadow-sm">
        <div class="border-b-2 border-[#1B4D3E] pb-4 mb-6 flex items-start justify-between">
            <div>
                <h1 class="text-lg font-bold text-[#1B4D3E]">BUKTI PENERIMAAN ZAKAT</h1>
                <p class="text-xs text-slate-600">Lembaga Amil Zakat • {{ $pembayaran->periode->nama_periode ?? '' }}</p>
            </div>
            <div class="text-right text-xs">
                <p class="font-bold text-slate-900">{{ $pembayaran->kode_transaksi }}</p>
                <p class="text-slate-500">{{ $pembayaran->tanggal_bayar?->format('d/m/Y H:i') }} WIB</p>
                <p class="mt-1 font-semibold uppercase">{{ $pembayaran->status->label() }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs mb-6">
            <div>
                <p class="text-slate-500">Telah diterima dari (Muzakki):</p>
                <p class="font-bold text-sm text-slate-900">{{ $pembayaran->muzakki->nama_lengkap ?? '-' }}</p>
                <p>{{ $pembayaran->muzakki->no_hp ?? '-' }}</p>
                <p class="text-slate-600">{{ $pembayaran->muzakki->alamat ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-slate-500">Metode Pembayaran:</p>
                <p class="font-bold text-slate-900">{{ $pembayaran->metodePembayaran->nama_metode ?? '-' }}</p>
                <p class="text-slate-600 mt-1">Petugas: {{ $pembayaran->petugasInput->name ?? '-' }}</p>
            </div>
        </div>

        <table class="w-full text-xs border border-slate-300 mb-6">
            <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                    <th class="p-2.5 text-left">Jenis Zakat</th>
                    <th class="p-2.5 text-left">Rincian</th>
                    <th class="p-2.5 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran->details as $detail)
                    <tr class="border-b border-slate-200">
                        <td class="p-2.5 font-semibold">{{ $detail->jenisZakat->nama_jenis ?? '-' }}</td>
                        <td class="p-2.5">
                            @if ($detail->jumlah_jiwa)
                                {{ $detail->jumlah_jiwa }} jiwa ({{ $detail->jiwa->pluck('nama_anggota')->implode(', ') }})
                            @else
                                {{ $detail->bentuk_bayar->label() }}
                            @endif
                        </td>
                        <td class="p-2.5 text-right font-bold">
                            @if ($detail->nominal_uang > 0)
                                Rp {{ number_format($detail->nominal_uang, 0, ',', '.') }}
                            @endif
                            @if ($detail->jumlah_beras_kg > 0)
                                {{ number_format($detail->jumlah_beras_kg, 2, ',', '.') }} kg beras
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex items-center justify-between text-xs pt-4 border-t border-slate-200">
            <p class="italic text-slate-600 max-w-sm">
                "Semoga Allah memberikan pahala atas apa yang engkau berikan dan menjadikannya penyuci bagimu serta memberkahi harta yang tersisa."
            </p>
            <div class="text-center">
                <p class="text-slate-500">Amil Penerima,</p>
                <p class="font-bold text-slate-900 mt-10">{{ $pembayaran->petugasInput->name ?? 'Petugas LAZ' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
