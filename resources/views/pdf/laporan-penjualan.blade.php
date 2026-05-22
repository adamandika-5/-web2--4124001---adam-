<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #2C1A0E; line-height: 1.5; }
    .page { padding: 28px; }

    .header { border-bottom: 2px solid #C66B3D; padding-bottom: 14px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start; }
    .logo { font-size: 20px; font-weight: 700; color: #2C1A0E; }
    .logo-sub { font-size: 10px; color: #B08B6E; margin-top: 2px; }
    .report-title { text-align: right; }
    .report-title h1 { font-size: 16px; font-weight: 700; color: #C66B3D; }
    .report-title p { font-size: 10px; color: #B08B6E; margin-top: 3px; }

    .summary-row { display: flex; gap: 10px; margin-bottom: 18px; }
    .summary-card { flex: 1; background: #FAF7F0; border: 1px solid #E8DCC7; border-radius: 6px; padding: 10px 12px; text-align: center; }
    .summary-label { font-size: 9px; color: #B08B6E; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
    .summary-val { font-size: 16px; font-weight: 700; color: #C66B3D; margin-top: 3px; }
    .summary-sub { font-size: 9px; color: #B08B6E; margin-top: 2px; }

    table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10.5px; }
    thead th { background: #2C1A0E; color: #E8DCC7; font-size: 9.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; padding: 7px 10px; text-align: left; }
    thead th.right { text-align: right; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #E8DCC7; vertical-align: middle; }
    tbody tr:nth-child(even) td { background: #FAF7F0; }
    tbody tr:last-child td { border-bottom: 2px solid #C66B3D; }
    .right { text-align: right; }
    .fw { font-weight: 700; }
    tfoot td { padding: 8px 10px; font-weight: 700; background: #F4EDE0; }

    .pill { display: inline-block; font-size: 9px; font-weight: 700; padding: 2px 7px; border-radius: 20px; }
    .pill-ok  { background: #EAF3DE; color: #3B6D11; }
    .pill-proc{ background: #FAEEDA; color: #633806; }
    .pill-pend{ background: #EFEFEF; color: #555; }
    .pill-batal{ background: #FCEBEB; color: #791F1F; }

    .footer { margin-top: 18px; border-top: 1px solid #E8DCC7; padding-top: 12px; display: flex; justify-content: space-between; font-size: 9.5px; color: #B08B6E; }
    .page-num { text-align: right; }
</style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="logo">🏗️ Sinar Alam</div>
            <div class="logo-sub">Toko Material Bangunan · Pasuruan, Jawa Timur</div>
        </div>
        <div class="report-title">
            <h1>LAPORAN PENJUALAN</h1>
            <p>Dicetak: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
        </div>
    </div>

    {{-- Ringkasan --}}
    @php
        $totalPendapatan = $pesanans->sum('total');
        $totalSelesai    = $pesanans->where('status','selesai')->count();
        $totalBatal      = $pesanans->where('status','batal')->count();
        $rataRata        = $totalSelesai > 0 ? $pesanans->where('status','selesai')->sum('total') / $totalSelesai : 0;
    @endphp
    <div class="summary-row">
        <div class="summary-card">
            <div class="summary-label">Total Pesanan</div>
            <div class="summary-val">{{ number_format($pesanans->count()) }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pesanan Selesai</div>
            <div class="summary-val">{{ number_format($totalSelesai) }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Total Pendapatan</div>
            <div class="summary-val">Rp {{ number_format($pesanans->where('status','selesai')->sum('total')/1000000, 1) }}Jt</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Rata-rata Order</div>
            <div class="summary-val">Rp {{ number_format($rataRata/1000, 1) }}K</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Dibatalkan</div>
            <div class="summary-val" style="color:#c03030">{{ number_format($totalBatal) }}</div>
        </div>
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:18%">No. Pesanan</th>
                <th style="width:18%">Pelanggan</th>
                <th style="width:13%">Kota</th>
                <th style="width:10%">Jenis Kirim</th>
                <th class="right" style="width:14%">Total</th>
                <th style="width:10%">Status</th>
                <th style="width:13%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanans as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw">{{ $p->nomor_pesanan }}</td>
                <td>{{ $p->user->name ?? '-' }}</td>
                <td>{{ $p->kota_tujuan }}</td>
                <td>{{ $p->jenis_pengiriman === 'armada' ? 'Armada' : 'Ekspedisi' }}</td>
                <td class="right fw">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                <td>
                    @php $pc = match($p->status) {
                        'selesai'=>'pill-ok','diproses'=>'pill-proc',
                        'batal'=>'pill-batal',default=>'pill-pend'
                    }; @endphp
                    <span class="pill {{ $pc }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="fw">TOTAL PENDAPATAN (Pesanan Selesai)</td>
                <td class="right fw" style="color:#C66B3D;font-size:12px">
                    Rp {{ number_format($pesanans->where('status','selesai')->sum('total'), 0, ',', '.') }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div>Laporan ini digenerate otomatis oleh sistem Sinar Alam · info@sinaralam.id</div>
        <div class="page-num">Sinar Alam © {{ date('Y') }}</div>
    </div>

</div>
</body>
</html>