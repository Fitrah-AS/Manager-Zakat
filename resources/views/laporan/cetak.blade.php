<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak - {{ $laporan['judul'] }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 p-6">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
        <div class="no-print mb-5 flex justify-end gap-2">
            <button type="button" onclick="window.print()" class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-bold text-white cursor-pointer">Cetak Sekarang</button>
            <button type="button" onclick="window.close()" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Tutup</button>
        </div>

        <div class="border-b-2 border-[#1B4D3E] pb-4 mb-5 flex items-start justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-[#1B4D3E]">Lembaga Amil Zakat</p>
                <h1 class="text-xl font-bold text-slate-900 mt-0.5">{{ $laporan['judul'] }}</h1>
                <p class="text-xs text-slate-600 mt-1">Periode: {{ $laporan['periodeLabel'] }}</p>
            </div>
            <div class="text-right text-xs text-slate-500">
                <p>Tanggal Cetak:</p>
                <p class="font-semibold text-slate-800">{{ now()->format('d/m/Y H:i') }} WIB</p>
            </div>
        </div>

        @if (! empty($laporan['ringkasan']))
            <div class="grid grid-cols-3 gap-4 mb-5">
                @foreach ($laporan['ringkasan'] as $item)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <p class="text-[11px] text-slate-500">{{ $item['label'] }}</p>
                        <p class="text-sm font-bold text-[#1B4D3E] mt-0.5">{{ $item['tampil'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <table class="w-full border-collapse text-xs">
            <thead>
                <tr class="bg-[#1B4D3E] text-white">
                    @foreach ($laporan['kolom'] as $kolom)
                        <th class="border border-slate-300 px-2.5 py-2 text-left font-semibold">{{ $kolom }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan['rows'] as $row)
                    <tr>
                        @foreach ($row as $cell)
                            <td class="border border-slate-300 px-2.5 py-1.5">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($laporan['kolom']) }}" class="border border-slate-300 px-3 py-6 text-center text-slate-400">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
