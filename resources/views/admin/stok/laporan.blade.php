@extends('layouts.admin')
@section('title','Laporan Stok')
@section('page_title','Laporan Stok Lengkap')
@section('breadcrumb','Inventaris › Stok › Laporan')

@section('content')

{{-- Tombol Export --}}
<div style="display:flex;gap:10px;justify-content:flex-end;margin-bottom:16px;flex-wrap:wrap">
    <a href="{{ route('admin.stok.laporan', ['format'=>'excel']) }}" class="btn btn-secondary btn-sm">
        📊 Export Excel
    </a>
    <a href="{{ route('admin.stok.laporan', ['format'=>'pdf']) }}" class="btn btn-secondary btn-sm">
        📄 Export PDF
    </a>
    <a href="{{ route('admin.stok.index') }}" class="btn btn-secondary btn-sm">
        ← Kembali ke Stok
    </a>
</div>

{{-- Ringkasan --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px">
    @php
        $totalProduk  = $produks->count();
        $stokAman     = $produks->where('stok', '>=', 20)->count();
        $stokRendah   = $produks->where('stok', '>', 0)->where('stok', '<', 20)->count();
        $stokHabis    = $produks->where('stok', 0)->count();
        $nilaiStok    = $produks->sum(fn($p) => $p->stok * $p->harga);
    @endphp
    @foreach([
        ['📦','Total Produk Aktif',$totalProduk,'var(--soil)'],
        ['✅','Stok Aman',$stokAman,'var(--moss)'],
        ['⚠️','Stok Rendah',$stokRendah,'var(--ochre)'],
        ['❌','Stok Habis',$stokHabis,'#c03030'],
    ] as [$ikon,$lbl,$val,$warna])
    <div style="background:#fff;border-radius:var(--r-lg);padding:16px 18px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="font-size:20px;margin-bottom:8px">{{ $ikon }}</div>
        <div style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">{{ $lbl }}</div>
        <div style="font-family:var(--fd);font-size:24px;font-weight:700;color:{{ $warna }}">{{ number_format($val) }}</div>
    </div>
    @endforeach
</div>

{{-- Nilai Total Stok --}}
<div style="background:var(--soil);border-radius:var(--r-lg);padding:20px 24px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;position:relative;overflow:hidden">
    <div class="grain"></div>
    <div style="position:relative;z-index:2">
        <div style="font-size:12px;color:rgba(232,220,199,.4);font-weight:700;text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px">Estimasi Nilai Total Stok</div>
        <div style="font-family:var(--fd);font-size:32px;font-weight:700;color:var(--terracotta)">
            Rp {{ number_format($nilaiStok, 0, ',', '.') }}
        </div>
    </div>
    <div style="position:relative;z-index:2;font-size:11px;color:rgba(232,220,199,.35)">
        Dihitung dari harga normal × stok saat ini<br>
        Per {{ now()->isoFormat('D MMMM Y, HH:mm') }}
    </div>
</div>

{{-- Tabel Lengkap --}}
<div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
    <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">
        Rincian Stok per Produk
    </div>
    <table class="data-tbl">
        <thead>
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Nilai Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $i => $p)
            <tr>
                <td style="color:var(--clay);font-size:12px">{{ $i + 1 }}</td>
                <td style="font-family:monospace;font-size:12px;color:var(--clay)">{{ $p->sku ?? '—' }}</td>
                <td>
                    <div style="font-weight:700;font-size:13px;color:var(--soil)">{{ $p->nama }}</div>
                </td>
                <td>
                    <span class="badge" style="background:rgba(176,139,110,.1);color:var(--clay);font-size:11px">
                        {{ $p->kategori->nama ?? '—' }}
                    </span>
                </td>
                <td>
                    <span style="font-family:var(--fd);font-size:16px;font-weight:700;
                          color:{{ $p->stok <= 0 ? '#c03030' : ($p->stok < 20 ? 'var(--ochre)' : 'var(--moss)') }}">
                        {{ number_format($p->stok) }}
                    </span>
                </td>
                <td style="color:var(--clay);font-size:13px">{{ $p->satuan }}</td>
                <td style="font-size:13px;color:var(--soil)">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td style="font-weight:700;color:var(--soil)">
                    Rp {{ number_format($p->stok * $p->harga, 0, ',', '.') }}
                </td>
                <td>
                    <span class="status-pill {{ $p->stok <= 0 ? 's-batal' : ($p->stok < 20 ? 's-pending' : 's-lunas') }}">
                        {{ $p->stok <= 0 ? 'Habis' : ($p->stok < 20 ? 'Rendah' : 'Aman') }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:var(--oat)">
                <td colspan="4" style="font-weight:700;font-size:13px;padding:12px 14px;color:var(--soil)">TOTAL</td>
                <td style="font-family:var(--fd);font-size:16px;font-weight:700;color:var(--soil);padding:12px 14px">
                    {{ number_format($produks->sum('stok')) }}
                </td>
                <td></td>
                <td></td>
                <td style="font-family:var(--fd);font-size:16px;font-weight:700;color:var(--terracotta);padding:12px 14px">
                    Rp {{ number_format($nilaiStok, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection