<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan — Sinar Alam</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #2d1f0e; }
        .header { text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #c4a882; }
        .header h1 { font-size: 20px; font-weight: bold; color: #2d1f0e; }
        .header p  { font-size: 11px; color: #7a6044; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th    { background: #2d1f0e; color: #e8dcc7; padding: 8px 10px; text-align: left; font-size: 10.5px; font-weight: bold; }
        td    { padding: 7px 10px; border-bottom: 1px solid #e8dcc7; vertical-align: top; }
        tr:nth-child(even) td { background: #faf7f3; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: bold; }
        .badge-selesai  { background: #dcfce7; color: #15803d; }
        .badge-pending  { background: #fef9c3; color: #a16207; }
        .badge-batal    { background: #fee2e2; color: #dc2626; }
        .summary { margin-top: 24px; display: table; width: 100%; }
        .summary-row { display: table-row; }
        .summary-cell { display: table-cell; padding: 6px 12px; border: 1px solid #c4a882; }
        .footer { margin-top: 32px; text-align: center; font-size: 10px; color: #7a6044; border-top: 1px solid #c4a882; padding-top: 12px; }
        .total-row td { font-weight: bold; background: #f5f0e8; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Penjualan — Sinar Alam</h1>
        <p>Toko Material Bangunan | Jl. Brawijaya No.74/203, Peterongan, Kec. Peterongan, Kabupaten Jombang, Jawa Timur 61481</p>
        <p>Dicetak: {{ now()->isoFormat('dddd, D MMMM Y HH:mm') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th class="text-center">Item</th>
                <th class="text-center">Status</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($pesanans as $i => $pesanan)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $pesanan->nomor_pesanan }}</td>
                <td>{{ $pesanan->user?->name ?? $pesanan->penerima ?? '-' }}</td>
                <td>{{ $pesanan->created_at?->format('d/m/Y') }}</td>
                <td class="text-center">{{ $pesanan->items?->count() ?? 0 }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $pesanan->status }}">
                        {{ ucfirst($pesanan->status) }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($pesanan->total, 0, ',', '.') }}</td>
            </tr>
            @php if($pesanan->status === 'selesai') $grandTotal += $pesanan->total; @endphp
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding:20px">Tidak ada data pesanan.</td>
            </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="6" class="text-right">Total Pendapatan (Status: Selesai)</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Laporan ini digenerate secara otomatis oleh sistem Sinar Alam.
        Harap periksa keakuratan data sebelum digunakan untuk keperluan resmi.
    </div>

</body>
</html>
