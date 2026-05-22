@extends('layouts.admin')
@section('title','Laporan Penjualan')
@section('page_title','Laporan & Statistik')
@section('breadcrumb','Admin › Laporan')

@section('content')

{{-- Filter periode --}}
<div style="background:#fff;border-radius:var(--r-lg);padding:20px 24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07);margin-bottom:20px">
    <form method="GET" action="{{ route('admin.laporan') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
        <div>
            <label class="form-lbl" style="display:block;margin-bottom:5px">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}"
                   class="form-inp" style="padding:8px 12px;font-size:13px">
        </div>
        <div>
            <label class="form-lbl" style="display:block;margin-bottom:5px">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ request('sampai', now()->format('Y-m-d')) }}"
                   class="form-inp" style="padding:8px 12px;font-size:13px">
        </div>
        <div>
            <label class="form-lbl" style="display:block;margin-bottom:5px">Periode Cepat</label>
            <select onchange="setPeriode(this.value)"
                    style="padding:8px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;cursor:pointer">
                <option value="">Pilih periode</option>
                <option value="bulan_ini">Bulan Ini</option>
                <option value="bulan_lalu">Bulan Lalu</option>
                <option value="3_bulan">3 Bulan Terakhir</option>
                <option value="tahun_ini">Tahun Ini</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm" style="align-self:flex-end">Tampilkan</button>
        <div style="margin-left:auto;display:flex;gap:8px;align-self:flex-end">
            <a href="{{ route('admin.laporan.pdf', request()->all()) }}"
               class="btn btn-secondary btn-sm">📄 Export PDF</a>
            <a href="{{ route('admin.laporan.excel', request()->all()) }}"
               class="btn btn-secondary btn-sm">📊 Export Excel</a>
        </div>
    </form>
</div>

@php
    use App\Models\{Pesanan, Produk, User, BookingAlat};
    $dari   = request('dari', now()->startOfMonth()->format('Y-m-d'));
    $sampai = request('sampai', now()->format('Y-m-d'));

    try {
        $pesanans       = Pesanan::whereBetween('created_at', [$dari.' 00:00:00', $sampai.' 23:59:59']);
        $totalPendapatan= (clone $pesanans)->where('status','selesai')->sum('total');
        $totalPesanan   = (clone $pesanans)->count();
        $pesananSelesai = (clone $pesanans)->where('status','selesai')->count();
        $pesananBatal   = (clone $pesanans)->where('status','batal')->count();
        $pelangganBaru  = User::where('role','user')->whereBetween('created_at', [$dari, $sampai])->count();
        $pendapatanSewa = BookingAlat::whereBetween('created_at', [$dari, $sampai])->where('status','selesai')->sum('total_bayar');
        $pesananList    = (clone $pesanans)->with('user')->withCount('items')->latest()->paginate(15);

        // Produk terlaris periode ini
        $produkTerlaris = \App\Models\PesananItem::whereBetween('created_at', [$dari, $sampai])
            ->select('nama_produk', \DB::raw('SUM(qty) as total_qty'), \DB::raw('SUM(subtotal) as total_nilai'))
            ->groupBy('nama_produk')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();
    } catch (\Exception $e) {
        $totalPendapatan= 0; $totalPesanan = 0;
        $pesananSelesai = 0; $pesananBatal = 0;
        $pelangganBaru  = 0; $pendapatanSewa = 0;
        $pesananList    = collect(); $produkTerlaris = collect();
    }
@endphp

{{-- Stat cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px">
    @foreach([
        ['💰','Pendapatan Bersih','Rp '.number_format($totalPendapatan/1000000,1).' Jt','rgba(198,107,61,.1)','var(--terracotta)'],
        ['📦','Total Pesanan',number_format($totalPesanan),'rgba(192,142,58,.1)','var(--ochre)'],
        ['✅','Pesanan Selesai',number_format($pesananSelesai),'rgba(96,108,56,.1)','var(--moss)'],
        ['👥','Pelanggan Baru',number_format($pelangganBaru),'rgba(123,155,174,.1)','#5A8FAE'],
    ] as [$ikon,$lbl,$val,$bg,$warna])
    <div style="background:#fff;border-radius:var(--r-lg);padding:18px 20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="width:40px;height:40px;background:{{ $bg }};border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:12px">{{ $ikon }}</div>
        <div style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">{{ $lbl }}</div>
        <div style="font-family:var(--fd);font-size:24px;font-weight:700;color:{{ $warna }}">{{ $val }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:16px">

    {{-- Tabel Pesanan --}}
    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
        <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">
            Pesanan Periode Ini
        </div>
        <table class="data-tbl">
            <thead>
                <tr><th>No. Pesanan</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tgl</th></tr>
            </thead>
            <tbody>
                @forelse($pesananList as $p)
                <tr>
                    <td>
                        <a href="{{ route('admin.pesanan.show', $p->id) }}"
                           style="color:var(--terracotta);font-weight:700;text-decoration:none;font-size:13px">
                            {{ $p->nomor_pesanan }}
                        </a>
                    </td>
                    <td style="font-size:13px">{{ $p->user->name ?? '-' }}</td>
                    <td style="font-weight:700;font-size:13px">Rp {{ number_format($p->total,0,',','.') }}</td>
                    <td>
                        @php $sc = match($p->status) {
                            'selesai'=>'s-lunas','diproses'=>'s-proses',
                            'pending'=>'s-pending','batal'=>'s-batal',default=>'s-pending'
                        }; @endphp
                        <span class="status-pill {{ $sc }}">{{ ucfirst($p->status) }}</span>
                    </td>
                    <td style="font-size:12px;color:var(--clay)">{{ $p->created_at->format('d M') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--clay)">Tidak ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists($pesananList,'hasPages') && $pesananList->hasPages())
        <div style="padding:12px 20px;border-top:1px solid rgba(176,139,110,.09);background:var(--oat)">
            {{ $pesananList->withQueryString()->links() }}
        </div>
        @endif
    </div>

    {{-- Produk Terlaris + Ringkasan --}}
    <div style="display:flex;flex-direction:column;gap:14px">

        <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15px;font-weight:500;color:var(--soil);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--sand)">
                🏆 Produk Terlaris
            </div>
            @forelse($produkTerlaris as $i => $p)
            <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid rgba(176,139,110,.06)">
                <div style="width:24px;height:24px;background:{{ $i===0?'var(--terracotta)':($i===1?'var(--clay)':'var(--sand)') }};border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:{{ $i<2?'#fff':'var(--clay)' }};flex-shrink:0">
                    {{ $i+1 }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;color:var(--soil);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $p->nama_produk }}</div>
                    <div style="font-size:11.5px;color:var(--clay)">{{ number_format($p->total_qty) }} terjual</div>
                </div>
                <div style="font-size:12.5px;font-weight:700;color:var(--soil);text-align:right;white-space:nowrap">
                    Rp {{ number_format($p->total_nilai/1000000,1) }}Jt
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:var(--clay);font-size:13px">Belum ada data</div>
            @endforelse
        </div>

        {{-- Ringkasan konversi --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:18px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-size:13px;font-weight:700;color:var(--soil);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--sand)">Ringkasan Konversi</div>
            <div style="display:flex;flex-direction:column;gap:10px">
                @php
                    $konversi  = $totalPesanan > 0 ? round(($pesananSelesai/$totalPesanan)*100, 1) : 0;
                    $rata2Nilai= $pesananSelesai > 0 ? $totalPendapatan/$pesananSelesai : 0;
                @endphp
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Tingkat Penyelesaian</span>
                    <span style="font-weight:700;color:var(--moss)">{{ $konversi }}%</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Rata-rata Nilai Order</span>
                    <span style="font-weight:700;color:var(--soil)">Rp {{ number_format($rata2Nilai,0,',','.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Pesanan Dibatalkan</span>
                    <span style="font-weight:700;color:#c03030">{{ $pesananBatal }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Pendapatan Sewa Alat</span>
                    <span style="font-weight:700;color:var(--soil)">Rp {{ number_format($pendapatanSewa,0,',','.') }}</span>
                </div>
                <div style="border-top:1px solid var(--sand);padding-top:10px;display:flex;justify-content:space-between;font-size:14px">
                    <span style="font-weight:700;color:var(--soil)">Total Pendapatan</span>
                    <span style="font-family:var(--fd);font-size:18px;font-weight:700;color:var(--terracotta)">
                        Rp {{ number_format(($totalPendapatan+$pendapatanSewa)/1000000,1) }}Jt
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function setPeriode(v) {
    const now  = new Date();
    let dari, sampai = now.toISOString().split('T')[0];

    if (v === 'bulan_ini') {
        dari = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
    } else if (v === 'bulan_lalu') {
        const fl = new Date(now.getFullYear(), now.getMonth()-1, 1);
        const ll = new Date(now.getFullYear(), now.getMonth(), 0);
        dari   = fl.toISOString().split('T')[0];
        sampai = ll.toISOString().split('T')[0];
    } else if (v === '3_bulan') {
        dari = new Date(now.getFullYear(), now.getMonth()-3, 1).toISOString().split('T')[0];
    } else if (v === 'tahun_ini') {
        dari = new Date(now.getFullYear(), 0, 1).toISOString().split('T')[0];
    } else return;

    document.querySelector('[name="dari"]').value   = dari;
    document.querySelector('[name="sampai"]').value = sampai;
}
</script>
@endpush