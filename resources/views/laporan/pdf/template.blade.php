<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $laporan['judul'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; }
        .header { border-bottom: 2px solid #1B4D3E; padding-bottom: 8px; margin-bottom: 12px; }
        .header h1 { margin: 2px 0; font-size: 15px; color: #1B4D3E; }
        .meta { font-size: 9px; color: #475569; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .summary-table td { border: 1px solid #cbd5e1; padding: 6px 8px; background: #f8fafc; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background: #1B4D3E; color: #ffffff; border: 1px solid #1B4D3E; padding: 5px 6px; text-align: left; font-size: 9px; }
        .data-table td { border: 1px solid #cbd5e1; padding: 5px 6px; font-size: 9px; }
        .footer { margin-top: 14px; font-size: 8.5px; color: #64748b; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="meta">LEMBAGA AMIL ZAKAT — SISTEM INFORMASI ZAKAT FITRAH &amp; MAL</div>
        <h1>{{ $laporan['judul'] }}</h1>
        <div class="meta">Periode: {{ $laporan['periodeLabel'] }} | Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</div>
    </div>

    @if (! empty($laporan['ringkasan']))
        <table class="summary-table">
            <tr>
                @foreach ($laporan['ringkasan'] as $item)
                    <td>
                        <div style="font-size: 8.5px; color: #64748b;">{{ $item['label'] }}</div>
                        <div style="font-size: 11px; font-weight: bold; color: #1B4D3E; margin-top: 2px;">{{ $item['tampil'] }}</div>
                    </td>
                @endforeach
            </tr>
        </table>
    @endif

    @if (! empty($laporan['rekapAsnaf']))
        <table class="data-table" style="margin-bottom: 12px;">
            <thead>
                <tr>
                    <th>Golongan Asnaf</th>
                    <th>Jumlah Penerima</th>
                    <th>Total Uang (Rp)</th>
                    <th>Total Beras (Kg)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan['rekapAsnaf'] as $rekap)
                    <tr>
                        <td>{{ $rekap['nama_asnaf'] }}</td>
                        <td>{{ $rekap['jumlah_penerima'] }}</td>
                        <td>Rp {{ number_format($rekap['total_uang'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['total_beras_kg'], 2, ',', '.') }} kg</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                @foreach ($laporan['kolom'] as $kolom)
                    <th>{{ $kolom }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan['rows'] as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($laporan['kolom']) }}" style="text-align: center; padding: 14px;">Belum ada data pada filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dokumen dihasilkan otomatis dari database Sistem Informasi Pengelolaan Zakat.</div>
</body>
</html>
