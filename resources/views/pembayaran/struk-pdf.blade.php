<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Zakat - {{ $pembayaran->kode_transaksi }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        .header { border-bottom: 2px solid #1B4D3E; padding-bottom: 10px; margin-bottom: 16px; }
        .title { font-size: 16px; font-weight: bold; color: #1B4D3E; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 7px; text-align: left; }
        th { background-color: #f1f5f9; }
        .text-right { text-align: right; }
        .footer { margin-top: 24px; font-size: 10px; color: #475569; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">BUKTI PEMBAYARAN ZAKAT</p>
        <p style="margin: 2px 0;">Kode Transaksi: <strong>{{ $pembayaran->kode_transaksi }}</strong> | Tanggal: {{ $pembayaran->tanggal_bayar?->format('d/m/Y H:i') }} WIB</p>
        <p style="margin: 2px 0;">Periode: {{ $pembayaran->periode->nama_periode ?? '-' }} | Status: <strong>{{ $pembayaran->status->label() }}</strong></p>
    </div>

    <p><strong>Muzakki:</strong> {{ $pembayaran->muzakki->nama_lengkap ?? '-' }} ({{ $pembayaran->muzakki->no_hp ?? '-' }})</p>
    <p><strong>Alamat:</strong> {{ $pembayaran->muzakki->alamat ?? '-' }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $pembayaran->metodePembayaran->nama_metode ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Jenis Zakat</th>
                <th>Rincian</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran->details as $detail)
                <tr>
                    <td>{{ $detail->jenisZakat->nama_jenis ?? '-' }}</td>
                    <td>
                        @if ($detail->jumlah_jiwa)
                            {{ $detail->jumlah_jiwa }} jiwa: {{ $detail->jiwa->pluck('nama_anggota')->implode(', ') }}
                        @else
                            {{ $detail->bentuk_bayar->label() }}
                        @endif
                    </td>
                    <td class="text-right">
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

    <div class="footer">
        <p>Petugas Penerima: {{ $pembayaran->petugasInput->name ?? '-' }}</p>
        @if ($pembayaran->verifikator)
            <p>Diverifikasi oleh: {{ $pembayaran->verifikator->name }} pada {{ $pembayaran->verified_at?->format('d/m/Y H:i') }} WIB</p>
        @endif
    </div>
</body>
</html>
