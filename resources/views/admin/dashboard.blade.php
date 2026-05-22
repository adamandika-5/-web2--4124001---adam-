@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Utama › Dashboard')

@section('content')

{{-- ── STATS CARDS ── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:20px;margin-bottom:28px">
    
    {{-- Card 1: Pendapatan --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm);display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden">
        <div style="font-size:12px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--clay);margin-bottom:12px">Pendapatan Bulan Ini</div>
        <div>
            <div style="font-family:var(--fd);font-size:26px;font-weight:700;color:var(--soil)">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
            <div style="display:flex;align-items:center;gap:6px;margin-top:6px;font-size:12.5px">
                @if($pendapatanGrowth >= 0)
                    <span style="color:#16a34a;font-weight:700">▲ +{{ $pendapatanGrowth }}%</span>
                @else
                    <span style="color:#dc2626;font-weight:700">▼ {{ $pendapatanGrowth }}%</span>
                @endif
                <span style="color:var(--clay)">vs bulan lalu</span>
            </div>
        </div>
        <div style="position:absolute;bottom:-10px;right:-10px;font-size:72px;opacity:0.04;user-select:none;pointer-events:none">💰</div>
    </div>

    {{-- Card 2: Pesanan --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm);display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden">
        <div style="font-size:12px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--clay);margin-bottom:12px">Pesanan Baru</div>
        <div>
            <div style="font-family:var(--fd);font-size:26px;font-weight:700;color:var(--soil)">{{ number_format($pesananBulanIni) }}</div>
            <div style="display:flex;align-items:center;gap:6px;margin-top:6px;font-size:12.5px">
                @if($pesananGrowth >= 0)
                    <span style="color:#16a34a;font-weight:700">▲ +{{ $pesananGrowth }}%</span>
                @else
                    <span style="color:#dc2626;font-weight:700">▼ {{ $pesananGrowth }}%</span>
                @endif
                <span style="color:var(--clay)">vs bulan lalu</span>
            </div>
        </div>
        <div style="position:absolute;bottom:-10px;right:-10px;font-size:72px;opacity:0.04;user-select:none;pointer-events:none">📦</div>
    </div>

    {{-- Card 3: Pelanggan Baru --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm);display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden">
        <div style="font-size:12px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--clay);margin-bottom:12px">Pelanggan Baru</div>
        <div>
            <div style="font-family:var(--fd);font-size:26px;font-weight:700;color:var(--soil)">{{ number_format($pelangganBaru) }}</div>
            <div style="margin-top:6px;font-size:12.5px;color:var(--clay)">Terdaftar bulan ini</div>
        </div>
        <div style="position:absolute;bottom:-10px;right:-10px;font-size:72px;opacity:0.04;user-select:none;pointer-events:none">👥</div>
    </div>

    {{-- Card 4: Sewa Aktif --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm);display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden">
        <div style="font-size:12px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--clay);margin-bottom:12px">Sewa Alat Aktif</div>
        <div>
            <div style="font-family:var(--fd);font-size:26px;font-weight:700;color:var(--soil)">{{ number_format($sewaAktif) }}</div>
            <div style="margin-top:6px;font-size:12.5px;color:var(--clay)">
                @if($sewaTerlambat > 0)
                    <span style="color:#dc2626;font-weight:700">⚠️ {{ $sewaTerlambat }} terlambat</span> pengembalian
                @else
                    Semua pengembalian lancar
                @endif
            </div>
        </div>
        <div style="position:absolute;bottom:-10px;right:-10px;font-size:72px;opacity:0.04;user-select:none;pointer-events:none">🛠️</div>
    </div>

</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:28px;align-items:start">

    {{-- ── GRAFIK PENDAPATAN ── --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h2 style="font-family:var(--fd);font-size:17px;color:var(--soil);margin:0">Tren Pendapatan Selesai (6 Bulan Terakhir)</h2>
            <span style="font-size:12px;color:var(--clay)">Dalam Rupiah (Rp)</span>
        </div>
        
        <div style="display:flex;align-items:flex-end;justify-content:space-between;height:200px;padding-top:20px;border-bottom:1.5px solid var(--sand);position:relative;margin-bottom:10px">
            @foreach($grafikBulanan as $g)
                @php
                    $persen = $grafikMax > 0 ? ($g['total'] / $grafikMax) * 100 : 0;
                    // pastikan bar punya tinggi minimal 4% jika total > 0 agar tidak gepeng banget
                    if ($g['total'] > 0 && $persen < 4) $persen = 4;
                @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;height:100%;justify-content:flex-end;margin:0 8px;position:relative">
                    {{-- Tooltip nilai --}}
                    <div class="grafik-val" style="position:absolute;bottom:calc({{ $persen }}% + 6px);background:var(--soil);color:#fff;padding:4px 8px;border-radius:var(--r-sm);font-size:10px;white-space:nowrap;font-weight:700;display:none;z-index:10;box-shadow:var(--sh-sm)">
                        Rp {{ number_format($g['total'], 0, ',', '.') }}
                    </div>
                    
                    {{-- Batang Grafik --}}
                    <div style="width:100%;height:{{ $persen }}%;background:{{ $g['aktif'] ? 'var(--terracotta)' : 'var(--clay)' }};opacity:{{ $g['aktif'] ? '1' : '0.45' }};border-radius:var(--r-sm) var(--r-sm) 0 0;cursor:pointer;transition:all .2s"
                         onmouseover="this.style.opacity='1'; this.previousElementSibling.style.display='block'"
                         onmouseout="this.style.opacity='{{ $g['aktif'] ? '1' : '0.45' }}'; this.previousElementSibling.style.display='none'">
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Label bulan --}}
        <div style="display:flex;justify-content:space-between;padding:0 8px">
            @foreach($grafikBulanan as $g)
                <div style="flex:1;text-align:center;font-size:12px;font-weight:700;color:var(--clay)">
                    {{ $g['label'] }}
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── NAVIGASI CEPAT (QUICK LINKS) ── --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)">
        <h2 style="font-family:var(--fd);font-size:17px;color:var(--soil);margin-bottom:18px;margin-top:0">Aksi & Navigasi Cepat</h2>
        <div style="display:flex;flex-direction:column;gap:10px">
            
            <a href="{{ route('admin.produk.index') }}" style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--oat);border-radius:var(--r-md);text-decoration:none;color:var(--soil);font-weight:600;font-size:13.5px;transition:transform .15s" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='none'">
                <span>📦 Manajemen Produk</span>
                <span style="font-size:12px;color:var(--clay)">Kelola item & SKU →</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}" style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--oat);border-radius:var(--r-md);text-decoration:none;color:var(--soil);font-weight:600;font-size:13.5px;transition:transform .15s" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='none'">
                <span>🗂️ Manajemen Kategori</span>
                <span style="font-size:12px;color:var(--clay)">Grup & Ikon →</span>
            </a>

            <a href="{{ route('admin.pesanan.index') }}" style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--oat);border-radius:var(--r-md);text-decoration:none;color:var(--soil);font-weight:600;font-size:13.5px;transition:transform .15s" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='none'">
                <span>💳 Proses Pesanan</span>
                <span style="font-size:12px;color:var(--clay)">Konfirmasi & update →</span>
            </a>

            <a href="{{ route('admin.supplier.index') }}" style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--oat);border-radius:var(--r-md);text-decoration:none;color:var(--soil);font-weight:600;font-size:13.5px;transition:transform .15s" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='none'">
                <span>🏭 Manajemen Supplier</span>
                <span style="font-size:12px;color:var(--clay)">Kontak penyedia →</span>
            </a>

            <div style="border-top:1px dashed rgba(176,139,110,.2);margin:8px 0;padding-top:12px;display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <a href="{{ route('admin.laporan') }}" class="btn btn-secondary" style="font-size:12.5px;padding:10px;text-align:center;justify-content:center;text-decoration:none">
                    📈 Laporan Penjualan
                </a>
                <a href="{{ route('admin.pengaturan') }}" class="btn btn-secondary" style="font-size:12.5px;padding:10px;text-align:center;justify-content:center;text-decoration:none">
                    ⚙️ Pengaturan Toko
                </a>
            </div>

        </div>
    </div>

</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px">

    {{-- ── TABEL PESANAN TERBARU ── --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm);overflow:hidden">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px">
            <h2 style="font-family:var(--fd);font-size:17px;color:var(--soil);margin:0">Pesanan Masuk Terbaru</h2>
            <a href="{{ route('admin.pesanan.index') }}" style="font-size:12.5px;color:var(--terracotta);text-decoration:none;font-weight:700">Lihat Semua →</a>
        </div>
        
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="background:var(--oat);border-bottom:1px solid rgba(176,139,110,.08)">
                    <th style="padding:10px 12px;text-align:left;color:var(--clay);font-weight:600">No. Pesanan</th>
                    <th style="padding:10px 12px;text-align:left;color:var(--clay);font-weight:600">Pelanggan</th>
                    <th style="padding:10px 12px;text-align:right;color:var(--clay);font-weight:600">Total</th>
                    <th style="padding:10px 12px;text-align:center;color:var(--clay);font-weight:600">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananTerbaru as $psn)
                <tr style="border-bottom:1px solid rgba(176,139,110,.05)" class="row-hover">
                    <td style="padding:10px 12px">
                        <a href="{{ route('admin.pesanan.show', $psn) }}" style="font-family:monospace;font-weight:700;color:var(--terracotta);text-decoration:none">
                            {{ $psn->nomor_pesanan }}
                        </a>
                    </td>
                    <td style="padding:10px 12px;color:var(--soil)">
                        <div style="font-weight:600">{{ $psn->user->name ?? $psn->penerima }}</div>
                        <div style="font-size:11px;color:var(--clay)">{{ $psn->items_count }} item</div>
                    </td>
                    <td style="padding:10px 12px;text-align:right;color:var(--soil);font-weight:700">
                        Rp {{ number_format($psn->total, 0, ',', '.') }}
                    </td>
                    <td style="padding:10px 12px;text-align:center">
                        @php $statusClass = match($psn->status) {
                            'selesai'=>'s-lunas','diproses'=>'s-proses','dikirim'=>'s-proses',
                            'pending'=>'s-pending','batal'=>'s-batal',default=>'s-pending'
                        }; @endphp
                        <span class="status-pill {{ $statusClass }}" style="font-size:11px;padding:2px 8px">
                            {{ $psn->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:24px;text-align:center;color:var(--clay)">Tidak ada pesanan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── ALARM STOK RENDAH ── --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm);overflow:hidden">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px">
            <h2 style="font-family:var(--fd);font-size:17px;color:var(--soil);margin:0">🚨 Warning Stok Rendah</h2>
            <a href="{{ route('admin.stok.index') }}?filter=low_stock" style="font-size:12.5px;color:var(--terracotta);text-decoration:none;font-weight:700">Update Stok →</a>
        </div>
        
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="background:var(--oat);border-bottom:1px solid rgba(176,139,110,.08)">
                    <th style="padding:10px 12px;text-align:left;color:var(--clay);font-weight:600">Nama Produk</th>
                    <th style="padding:10px 12px;text-align:left;color:var(--clay);font-weight:600">Kategori</th>
                    <th style="padding:10px 12px;text-align:right;color:var(--clay);font-weight:600">Sisa Stok</th>
                    <th style="padding:10px 12px;text-align:center;color:var(--clay);font-weight:600">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stokRendah as $prod)
                <tr style="border-bottom:1px solid rgba(176,139,110,.05)">
                    <td style="padding:10px 12px">
                        <a href="{{ route('admin.produk.edit', $prod) }}" style="font-weight:600;color:var(--soil);text-decoration:none">
                            {{ $prod->nama }}
                        </a>
                        <div style="font-size:11px;color:var(--clay);font-family:monospace">{{ $prod->sku }}</div>
                    </td>
                    <td style="padding:10px 12px;color:var(--clay)">
                        {{ $prod->kategori->nama ?? '—' }}
                    </td>
                    <td style="padding:10px 12px;text-align:right;font-weight:700;color:{{ $prod->stok == 0 ? '#dc2626' : 'var(--soil)' }}">
                        {{ number_format($prod->stok) }} {{ $prod->satuan }}
                    </td>
                    <td style="padding:10px 12px;text-align:center">
                        @if($prod->stok == 0)
                            <span style="background:rgba(220,38,38,.1);color:#dc2626;padding:2px 8px;border-radius:99px;font-size:11px;font-weight:700">Habis</span>
                        @else
                            <span style="background:rgba(217,119,6,.1);color:#d97706;padding:2px 8px;border-radius:99px;font-size:11px;font-weight:700">Kritis</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:24px;text-align:center;color:#16a34a;font-weight:600">✓ Semua stok produk aman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- ── LOG AKTIVITAS TERBARU ── --}}
<div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm);margin-bottom:12px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px">
        <h2 style="font-family:var(--fd);font-size:17px;color:var(--soil);margin:0">Aktivitas Sistem Terbaru</h2>
        <a href="{{ route('admin.activity-log') }}" style="font-size:12.5px;color:var(--terracotta);text-decoration:none;font-weight:700">Lihat Logs →</a>
    </div>
    
    <div style="display:flex;flex-direction:column;gap:12px">
        @forelse($aktivitasTerbaru as $log)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--oat);border-radius:var(--r-sm);font-size:13px">
            <div style="display:flex;align-items:center;gap:10px">
                <span style="font-size:16px">⚡</span>
                <span style="color:var(--soil);font-weight:600">{{ $log->user->name ?? 'System / Anonymous' }}</span>
                <span style="color:var(--clay)">{{ $log->activity }}</span>
            </div>
            <span style="font-size:12px;color:var(--clay)">{{ $log->created_at->diffForHumans() }}</span>
        </div>
        @empty
        <div style="padding:18px;text-align:center;color:var(--clay);font-size:13.5px">Belum ada log aktivitas masuk.</div>
        @endforelse
    </div>
</div>

@endsection
