@extends('layouts.admin')
@section('title','Manajemen Pembayaran')
@section('page_title','Verifikasi Pembayaran')
@section('breadcrumb','Utama › Pembayaran')

@section('content')
{{--
  Eager loading di controller:
  Pembayaran::with(['pesanan.user','pesanan.items','dikonfirmasiOleh'])
             ->latest()->paginate(20)
--}}

{{-- Stat strip --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px">
    @foreach([
        ['⏳','Menunggu Konfirmasi',$menunggu,'rgba(192,142,58,.1)','var(--ochre)'],
        ['✅','Dikonfirmasi Hari Ini',$dikonfirmasiHariIni,'rgba(96,108,56,.1)','var(--moss)'],
        ['💰','Total Diterima Bulan Ini',$totalBulanIni,'rgba(198,107,61,.1)','var(--terracotta)'],
    ] as [$ikon,$label,$val,$bg,$warna])
    <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="width:42px;height:42px;background:{{ $bg }};border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:12px">{{ $ikon }}</div>
        <div style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">{{ $label }}</div>
        <div style="font-family:var(--fd);font-size:22px;font-weight:700;color:{{ $warna }}">
            @if(is_numeric($val) && $val > 9999)
                Rp {{ number_format($val/1000000,1) }} Jt
            @else
                {{ number_format($val) }}
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div style="display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.pembayaran.index') }}" style="display:flex;gap:10px;flex:1">
        <select name="status" onchange="this.form.submit()"
                style="padding:8px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;cursor:pointer">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status')==='menunggu'?'selected':'' }}>Menunggu</option>
            <option value="dikonfirmasi" {{ request('status')==='dikonfirmasi'?'selected':'' }}>Dikonfirmasi</option>
            <option value="ditolak" {{ request('status')==='ditolak'?'selected':'' }}>Ditolak</option>
        </select>
        <select name="metode" onchange="this.form.submit()"
                style="padding:8px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;cursor:pointer">
            <option value="">Semua Metode</option>
            <option value="transfer_bank" {{ request('metode')==='transfer_bank'?'selected':'' }}>Transfer Bank</option>
            <option value="qris" {{ request('metode')==='qris'?'selected':'' }}>QRIS</option>
            <option value="cod" {{ request('metode')==='cod'?'selected':'' }}>COD</option>
            <option value="dp" {{ request('metode')==='dp'?'selected':'' }}>DP</option>
        </select>
    </form>
</div>

{{-- Tabel --}}
<div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
    <table class="data-tbl">
        <thead>
            <tr>
                <th>Pesanan</th><th>Pelanggan</th><th>Metode</th>
                <th>Jumlah</th><th>Bukti</th><th>Waktu</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayarans as $b)
            <tr>
                <td>
                    <a href="{{ route('admin.pesanan.show', $b->pesanan->id) }}"
                       style="font-weight:700;color:var(--terracotta);text-decoration:none;font-size:13px">
                        {{ $b->pesanan->nomor_pesanan }}
                    </a>
                    <div style="font-size:11px;color:var(--clay)">{{ $b->pesanan->items->count() }} produk</div>
                </td>
                <td>
                    <div style="font-size:13px;font-weight:600;color:var(--soil)">{{ $b->pesanan->user->name }}</div>
                    <div style="font-size:11.5px;color:var(--clay)">{{ $b->pesanan->user->email }}</div>
                </td>
                <td style="font-size:13px">
                    {{ match($b->metode) {
                        'transfer_bank'=>'🏦 Transfer Bank',
                        'qris'=>'📱 QRIS',
                        'cod'=>'💵 COD',
                        'dp'=>'💰 DP',
                        default=>ucfirst($b->metode)
                    } }}
                    @if($b->bank)<div style="font-size:11px;color:var(--clay)">{{ $b->bank }}</div>@endif
                </td>
                <td style="font-weight:700;color:var(--soil)">Rp {{ number_format($b->jumlah,0,',','.') }}</td>
                <td>
                    @if($b->bukti_path)
                        <a href="{{ asset('storage/'.$b->bukti_path) }}" target="_blank"
                           style="display:block;width:48px;height:48px;overflow:hidden;border-radius:var(--r-sm);border:1.5px solid rgba(176,139,110,.2)">
                            <img src="{{ asset('storage/'.$b->bukti_path) }}" style="width:100%;height:100%;object-fit:cover" alt="Bukti">
                        </a>
                    @else
                        <span style="font-size:12px;color:var(--concrete)">—</span>
                    @endif
                </td>
                <td style="font-size:12px;color:var(--clay)">{{ $b->created_at->diffForHumans() }}</td>
                <td>
                    <span class="status-pill {{ $b->status==='dikonfirmasi'?'s-lunas':($b->status==='ditolak'?'s-batal':'s-pending') }}">
                        {{ ucfirst($b->status) }}
                    </span>
                </td>
                <td>
                    @if($b->status === 'menunggu')
                    <div class="act-btns">
                        <form action="{{ route('admin.pembayaran.konfirmasi', $b->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="act-btn" title="Konfirmasi" style="background:rgba(96,108,56,.1)">
                                <svg viewBox="0 0 24 24" stroke="var(--moss)"><polyline points="20 6 9 17 4 12"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.pembayaran.tolak', $b->id) }}" method="POST"
                              onsubmit="return confirm('Tolak pembayaran ini?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="act-btn danger" title="Tolak">
                                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </form>
                    </div>
                    @else
                        <span style="font-size:11.5px;color:var(--clay)">
                            @if($b->dikonfirmasiOleh) oleh {{ $b->dikonfirmasiOleh->name }} @endif
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--clay)">Tidak ada data pembayaran</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-top:1px solid rgba(176,139,110,.09);background:var(--oat)">
        <div style="font-size:13px;color:var(--clay)">{{ $pembayarans->total() }} pembayaran</div>
        <div style="display:flex;gap:4px">
            @if(!$pembayarans->onFirstPage())<a href="{{ $pembayarans->previousPageUrl() }}" class="pag-btn">‹</a>@endif
            @foreach($pembayarans->getUrlRange(max(1,$pembayarans->currentPage()-2),min($pembayarans->lastPage(),$pembayarans->currentPage()+2)) as $p=>$u)
                @if($p==$pembayarans->currentPage())<button class="pag-btn active">{{$p}}</button>
                @else<a href="{{$u}}" class="pag-btn">{{$p}}</a>@endif
            @endforeach
            @if($pembayarans->hasMorePages())<a href="{{ $pembayarans->nextPageUrl() }}" class="pag-btn">›</a>@endif
        </div>
    </div>
</div>
@endsection