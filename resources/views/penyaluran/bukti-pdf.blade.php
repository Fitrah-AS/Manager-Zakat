<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Penyaluran - {{ $penyaluran->kode_penyaluran }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        .header { border-bottom: 2px solid #1B4D3E; padding-bottom: 10px; margin-bottom: 14px; }
        .title { font-size: 15px; font-weight: bold; color: #1B4D3E; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px; text-align: left; }
        th { background-color: #f1f5f9; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">BUKTI PENYALURAN ZAKAT</p>
        <p style="margin:2px 0;">Kode: <strong>{{ $penyaluran->kode_penyaluran }}</strong> | Tanggal: {{ $penyaluran->tanggal_penyaluran?->format('d/m/Y H:i') }} WIB</p>
        <p style="margin:2px 0;">Periode: {{ $penyaluran->periode->nama_periode ?? '-' }} | Status: <strong>{{ $penyaluran->status->label() }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mustahik</th>
                <th>Asnaf</th>
                <th>Jenis Zakat</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penyaluran->details as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->mustahik->nama_lengkap ?? '-' }}</td>
                    <td>{{ $d->asnaf->nama_asnaf ?? '-' }}</td>
                    <td>{{ $d->jenisZakat->nama_jenis ?? '-' }}</td>
                    <td class="text-right">
                        @if ($d->bentuk_salur->value === 'uang')
                            Rp {{ number_format($d->nominal_uang, 0, ',', '.') }}
                        @else
                            {{ number_format($d->jumlah_beras_kg, 2, ',', '.') }} kg
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:20px; font-size:10px;">Petugas Penyalur: {{ $penyaluran->petugas->name ?? '-' }}</p>
</body>
</html>
