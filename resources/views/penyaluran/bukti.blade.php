<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Penyaluran Zakat - {{ $penyaluran->kode_penyaluran }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print { .no-print { display: none !important; } body { background: #fff !important; } }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 p-6">
    <div class="max-w-3xl mx-auto mb-4 flex justify-end no-print">
        <button onclick="window.print()" class="rounded-lg bg-[#1B4D3E] text-white px-4 py-2 text-xs font-bold cursor-pointer">Cetak Bukti</button>
    </div>

    <div class="max-w-3xl mx-auto bg-white rounded-xl border border-slate-300 p-8 shadow-sm">
        <div class="border-b-2 border-[#1B4D3E] pb-4 mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-lg font-bold text-[#1B4D3E]">BERITA ACARA &amp; BUKTI PENYALURAN ZAKAT</h1>
                <p class="text-xs text-slate-600">{{ $penyaluran->periode->nama_periode ?? '-' }}</p>
            </div>
            <div class="text-right text-xs">
                <p class="font-bold">{{ $penyaluran->kode_penyaluran }}</p>
                <p>{{ $penyaluran->tanggal_penyaluran?->format('d/m/Y H:i') }} WIB</p>
                <p class="font-semibold uppercase mt-0.5">{{ $penyaluran->status->label() }}</p>
            </div>
        </div>

        <table class="w-full text-xs border border-slate-300 mb-6">
            <thead class="bg-slate-100 border-b border-slate-300">
                <tr>
                    <th class="p-2 text-left">No</th>
                    <th class="p-2 text-left">Mustahik</th>
                    <th class="p-2 text-left">Asnaf</th>
                    <th class="p-2 text-left">Jenis Zakat</th>
                    <th class="p-2 text-right">Jumlah</th>
                    <th class="p-2 text-center">Paraf</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penyaluran->details as $i => $d)
                    <tr class="border-b border-slate-200">
                        <td class="p-2">{{ $i + 1 }}</td>
                        <td class="p-2 font-semibold">{{ $d->mustahik->nama_lengkap ?? '-' }}</td>
                        <td class="p-2">{{ $d->asnaf->nama_asnaf ?? '-' }}</td>
                        <td class="p-2">{{ $d->jenisZakat->nama_jenis ?? '-' }}</td>
                        <td class="p-2 text-right font-bold">
                            @if ($d->bentuk_salur->value === 'uang')
                                Rp {{ number_format($d->nominal_uang, 0, ',', '.') }}
                            @else
                                {{ number_format($d->jumlah_beras_kg, 2, ',', '.') }} kg
                            @endif
                        </td>
                        <td class="p-2 text-center text-slate-400">............</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-between text-xs pt-4">
            <p class="text-slate-600">{{ $penyaluran->keterangan ?? 'Disalurkan kepada mustahik berstatus layak sesuai hasil survei.' }}</p>
            <div class="text-center shrink-0 ml-6">
                <p class="text-slate-500">Petugas Penyalur,</p>
                <p class="font-bold mt-10">{{ $penyaluran->petugas->name ?? '-' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
