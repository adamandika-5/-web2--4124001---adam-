<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok — Sinar Alam</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #2d1f0e; background: #fff; }

        .header { text-align: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 2.5px solid #8b6914; }
        .header h1 { font-size: 18px; font-weight: 800; color: #2d1f0e; letter-spacing: .5px; }
        .header .sub { font-size: 10.5px; color: #7a6044; margin-top: 3px; }
        .header .tanggal { font-size: 10px; color: #a0855a; margin-top: 6px; font-style: italic; }

        .stats { display: flex; gap: 0; margin-bottom: 18px; border: 1px solid #c4a882; border-radius: 6px; overflow: hidden; }
        .stat-box { flex: 1; padding: 10px 14px; text-align: center; border-right: 1px solid #c4a882; }
        .stat-box:last-child { border-right: none; }
        .stat-box .val { font-size: 20px; font-weight: 800; }
        .stat-box .lbl { font-size: 9.5px; color: #7a6044; margin-top: 2px; text-transform: uppercase; letter-spacing: .05em; }
        .val-total { color: #2d1f0e; }
        .val-aman  { color: #16a34a; }
        .val-rendah{ color: #b45309; }
        .val-habis { color: #c03030; }

        .nilai-box { background: #2d1f0e; color: #e8dcc7; border-radius: 6px; padding: 14px 20px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; }
        .nilai-box .lbl { font-size: 10px; opacity: .5; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 4px; }
        .nilai-box .val { font-size: 24px; font-weight: 800; color: #c8763d; }
        .nilai-box .note { font-size: 9.5px; opacity: .4; text-align: right; }

        table { width: 100%; border-collapse: collapse; }
        thead th { background: #2d1f0e; color: #e8dcc7; padding: 8px 10px; text-align: left; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #ede4d5; font-size: 11px; vertical-align: middle; }
        tbody tr:nth-child(even) td { background: #faf7f0; }
        tfoot td { padding: 9px 10px; background: #f5f0e8; font-weight: 700; font-size: 12px; border-top: 2px solid #c4a882; }

        .badge { display: inline-block; padding: 2px 9px; border-radius: 99px; font-size: 9.5px; font-weight: 700; }
        .badge-aman   { background: #dcfce7; color: #15803d; }
        .badge-rendah { background: #fef3c7; color: #92400e; }
        .badge-habis  { background: #fee2e2; color: #991b1b; }

        .monospace { font-family: 'Courier New', monospace; font-size: 10.5px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .footer { margin-top: 24px; text-align: center; font-size: 9.5px; color: #a0855a; border-top: 1px solid #e8dcc7; padding-top: 10px; }

        .print-btn { position: fixed; top: 16px; right: 16px; background: #2d1f0e; color: #e8dcc7; border: none; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; z-index: 999; }
        .print-btn:hover { background: #c8763d; }

        @media print {
            .print-btn { display: none; }
            body { font-size: 10px; }
            .nilai-box .val { font-size: 18px; }
        }

        @page { margin: 18mm; }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

@php
    $totalProduk = $produks->count();
    $stokAman    = $produks->where('stok', '>=', 20)->count();
    $stokRendah  = $produks->where('stok', '>', 0)->where('stok', '<', 20)->count();
    $stokHabis   = $produks->where('stok', '<=', 0)->count();
    $nilaiStok   = $produks->sum(fn($p) => $p->stok * $p->harga);
@endphp

{{-- Header --}}
<div class="header">
    <h1>📦 Laporan Stok Produk — Sinar Alam</h1>
    <div class="sub">Toko Material Bangunan | Jl. Brawijaya No.74/203, Peterongan, Jombang, Jawa Timur</div>
    <div class="tanggal">Dicetak: {{ now()->isoFormat('dddd, D MMMM Y — HH:mm') }} WIB</div>
</div>

{{-- Stats ringkasan --}}
<div class="stats">
    <div class="stat-box">
        <div class="val val-total">{{ number_format($totalProduk) }}</div>
        <div class="lbl">Total Produk</div>
    </div>
    <div class="stat-box">
        <div class="val val-aman">{{ number_format($stokAman) }}</div>
        <div class="lbl">Stok Aman</div>
    </div>
    <div class="stat-box">
        <div class="val val-rendah">{{ number_format($stokRendah) }}</div>
        <div class="lbl">Stok Rendah</div>
    </div>
    <div class="stat-box">
        <div class="val val-habis">{{ number_format($stokHabis) }}</div>
        <div class="lbl">Stok Habis</div>
    </div>
</div>

{{-- Nilai stok --}}
<div class="nilai-box">
    <div>
        <div class="lbl">Estimasi Nilai Total Stok</div>
        <div class="val">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</div>
    </div>
    <div class="note">
        Dihitung dari harga normal × stok<br>
        Per {{ now()->isoFormat('D MMMM Y, HH:mm') }}
    </div>
</div>

{{-- Tabel --}}
<table>
    <thead>
        <tr>
            <th style="width:28px">No</th>
            <th style="width:80px">SKU</th>
            <th>Nama Produk</th>
            <th style="width:100px">Kategori</th>
            <th class="text-center" style="width:48px">Stok</th>
            <th style="width:46px">Satuan</th>
            <th class="text-right" style="width:90px">Harga (Rp)</th>
            <th class="text-right" style="width:100px">Nilai Stok (Rp)</th>
            <th class="text-center" style="width:58px">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($produks as $i => $p)
        <tr>
            <td class="text-center" style="color:#a0855a">{{ $i + 1 }}</td>
            <td class="monospace" style="color:#7a6044">{{ $p->sku ?? '—' }}</td>
            <td style="font-weight:600;color:#2d1f0e">{{ $p->nama }}</td>
            <td style="color:#7a6044;font-size:10px">{{ optional($p->kategori)->nama ?? '—' }}</td>
            <td class="text-center" style="font-weight:800;font-size:13px;
                color:{{ $p->stok <= 0 ? '#c03030' : ($p->stok < 20 ? '#b45309' : '#16a34a') }}">
                {{ number_format($p->stok) }}
            </td>
            <td style="color:#7a6044">{{ $p->satuan }}</td>
            <td class="text-right">{{ number_format($p->harga, 0, ',', '.') }}</td>
            <td class="text-right" style="font-weight:700">{{ number_format($p->stok * $p->harga, 0, ',', '.') }}</td>
            <td class="text-center">
                @if($p->stok <= 0)
                    <span class="badge badge-habis">Habis</span>
                @elseif($p->stok < 20)
                    <span class="badge badge-rendah">Rendah</span>
                @else
                    <span class="badge badge-aman">Aman</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center" style="padding:24px;color:#a0855a;font-style:italic">
                Belum ada data stok produk.
            </td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-right" style="color:#7a6044;font-size:11px;font-weight:600">TOTAL KESELURUHAN</td>
            <td class="text-center" style="font-size:14px;color:#2d1f0e">{{ number_format($produks->sum('stok')) }}</td>
            <td></td>
            <td></td>
            <td class="text-right" style="font-size:13px;color:#c8763d">
                Rp {{ number_format($nilaiStok, 0, ',', '.') }}
            </td>
            <td></td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    Laporan ini digenerate otomatis oleh sistem Sinar Alam. Periksa keakuratan data sebelum digunakan untuk keperluan resmi.
</div>

<script>
    // Auto-print saat halaman load (opsional — bisa diaktifkan jika mau langsung cetak)
    // window.addEventListener('load', () => setTimeout(() => window.print(), 500));
</script>

</body>
</html>
