@extends('layouts.admin')
@section('title','Manajemen Pesanan')
@section('page_title','Manajemen Pesanan')
@section('breadcrumb','Utama › Pesanan')

@section('content')

{{-- Stat strip --}}
<div class="adm-stat-strip" style="grid-template-columns:repeat(5,1fr)">
    @foreach([
        ['label'=>'Semua','val'=>$total,'status'=>'','color'=>'var(--soil)'],
        ['label'=>'Menunggu','val'=>$totalPending,'status'=>'pending','color'=>'var(--concrete)'],
        ['label'=>'Diproses','val'=>$totalDiproses,'status'=>'diproses','color'=>'var(--ochre)'],
        ['label'=>'Dikirim','val'=>$totalDikirim,'status'=>'dikirim','color'=>'#7B9BAE'],
        ['label'=>'Selesai','val'=>$totalSelesai,'status'=>'selesai','color'=>'var(--moss)'],
    ] as $s)
    <a href="{{ route('admin.pesanan.index', ['status'=>$s['status']]) }}"
       class="adm-stat-item {{ request('status')===$s['status'] ? 'active' : '' }}">
        <div class="adm-stat-lbl">{{ $s['label'] }}</div>
        <div class="adm-stat-val" style="color:{{ $s['color'] }}">{{ number_format($s['val']) }}</div>
    </a>
    @endforeach
</div>

{{-- Toolbar --}}
<div class="adm-toolbar">
    <form method="GET" action="{{ route('admin.pesanan.index') }}" style="display:flex;gap:8px;flex:1;flex-wrap:wrap">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div style="position:relative;flex:1;min-width:200px">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--clay);pointer-events:none;width:15px;height:15px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="No. pesanan, nama pelanggan..."
                   class="form-inp" style="padding-left:36px;font-size:13px">
        </div>
        <select name="pengiriman" onchange="this.form.submit()" class="form-inp" style="font-size:13px;padding:8px 12px">
            <option value="">Semua Pengiriman</option>
            <option value="armada" {{ request('pengiriman')==='armada'?'selected':'' }}>Armada Sendiri</option>
            <option value="ekspedisi" {{ request('pengiriman')==='ekspedisi'?'selected':'' }}>Ekspedisi</option>
        </select>
        <select name="bayar" onchange="this.form.submit()" class="form-inp" style="font-size:13px;padding:8px 12px">
            <option value="">Semua Pembayaran</option>
            <option value="menunggu" {{ request('bayar')==='menunggu'?'selected':'' }}>Menunggu</option>
            <option value="lunas" {{ request('bayar')==='lunas'?'selected':'' }}>Lunas</option>
        </select>
        <input type="date" name="tgl" value="{{ request('tgl') }}" class="form-inp" style="font-size:13px;padding:8px 12px">
    </form>
    <a href="{{ route('admin.pesanan.export') }}" class="btn btn-secondary btn-sm">📤 Export</a>
</div>

{{-- Tabel --}}
<div class="adm-tbl-wrap">
    <table class="data-tbl">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Pengiriman</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{--
              Eager loading diterapkan di controller:
              Pesanan::with(['user', 'items.produk', 'pembayaran'])
                      ->withCount('items')
                      ->latest()->paginate(20)
            --}}
            @forelse($pesanans as $pesanan)
            <tr class="row-hover">
                <td>
                    <strong style="font-size:13px">{{ $pesanan->nomor_pesanan }}</strong>
                    <div style="font-size:11px;color:var(--clay)">{{ $pesanan->created_at->format('d M Y, H:i') }}</div>
                </td>
                <td>
                    <div style="font-size:13px;font-weight:600;color:var(--soil)">{{ $pesanan->user->name }}</div>
                    <div style="font-size:11.5px;color:var(--clay)">{{ $pesanan->kota_tujuan }}</div>
                </td>
                <td style="font-size:12.5px;max-width:180px">
                    {{ Str::limit($pesanan->ringkasan_produk, 45) }}
                    @if($pesanan->items_count > 1)
                        <div style="font-size:11px;color:var(--clay)">{{ $pesanan->items_count }} item total</div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:700;color:var(--soil)">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</div>
                    @if($pesanan->diskon_voucher > 0)
                        <div style="font-size:11px;color:var(--moss)">Hemat Rp {{ number_format($pesanan->diskon_voucher, 0, ',', '.') }}</div>
                    @endif
                </td>
                <td style="font-size:12.5px">
                    @if($pesanan->jenis_pengiriman === 'armada')
                        🚛 Armada
                    @else
                        📦 {{ $pesanan->ekspedisi ?? 'Ekspedisi' }}
                    @endif
                </td>
                <td>
                    @php $bayarClass = match($pesanan->status_pembayaran) {
                        'lunas' => 's-lunas', 'dp' => 's-proses',
                        'menunggu' => 's-pending', default => 's-batal'
                    }; $bayarLabel = match($pesanan->status_pembayaran) {
                        'lunas' => 'Lunas', 'dp' => 'DP', 'menunggu' => 'Menunggu', 'refund' => 'Refund', default => ucfirst($pesanan->status_pembayaran)
                    }; @endphp
                    <span class="status-pill {{ $bayarClass }}">{{ $bayarLabel }}</span>
                </td>
                <td>
                    @php $statusClass = match($pesanan->status) {
                        'selesai'=>'s-lunas','diproses'=>'s-proses',
                        'dikirim'=>'s-proses','pending'=>'s-pending',
                        'batal'=>'s-batal', default=>'s-pending'
                    }; $statusLabel = match($pesanan->status) {
                        'selesai'=>'Selesai','diproses'=>'Diproses',
                        'dikirim'=>'Dikirim','dikonfirmasi'=>'Dikonfirmasi',
                        'pending'=>'Pending','batal'=>'Batal', default=>ucfirst($pesanan->status)
                    }; @endphp
                    <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td>
                    <div class="act-btns">
                        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="act-btn" title="Detail">
                            <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <button onclick="toggleStatusModal({{ $pesanan->id }}, '{{ $pesanan->status }}')" class="act-btn" title="Update Status">
                            <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
                        </button>
                        <a href="{{ route('pesanan.invoice', $pesanan->nomor_pesanan) }}" class="act-btn" title="Invoice PDF" target="_blank">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--clay)">Tidak ada pesanan ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="adm-tbl-footer">
        <div class="adm-tbl-footer-info">
            Menampilkan {{ $pesanans->firstItem() ?? 0 }}–{{ $pesanans->lastItem() ?? 0 }} dari {{ $pesanans->total() }} pesanan
        </div>
        <div style="display:flex;gap:4px">
            @if($pesanans->onFirstPage())
                <button class="pag-btn" disabled style="opacity:.4">‹</button>
            @else
                <a href="{{ $pesanans->previousPageUrl() }}" class="pag-btn">‹</a>
            @endif
            @foreach($pesanans->getUrlRange(max(1,$pesanans->currentPage()-2), min($pesanans->lastPage(),$pesanans->currentPage()+2)) as $page => $url)
                @if($page == $pesanans->currentPage())
                    <button class="pag-btn active">{{ $page }}</button>
                @else
                    <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                @endif
            @endforeach
            @if($pesanans->hasMorePages())
                <a href="{{ $pesanans->nextPageUrl() }}" class="pag-btn">›</a>
            @else
                <button class="pag-btn" disabled style="opacity:.4">›</button>
            @endif
        </div>
    </div>
</div>

{{-- Modal Update Status --}}
<div id="statusModal" style="display:none;position:fixed;inset:0;background:rgba(44,26,14,.5);z-index:9999;display:flex;align-items:center;justify-content:center;display:none">
    <div style="background:#fff;border-radius:var(--r-xl);padding:32px;width:100%;max-width:420px;box-shadow:var(--sh-lg)">
        <div style="font-family:var(--fd);font-size:20px;font-weight:500;color:var(--soil);margin-bottom:20px">Update Status Pesanan</div>
        <form id="statusForm" method="POST">
            @csrf @method('PATCH')
            <div class="form-grp">
                <label class="form-lbl">Status Pesanan</label>
                <select name="status" id="statusSelect" class="form-inp">
                    <option value="pending">Pending</option>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="diproses">Diproses</option>
                    <option value="dikirim">Dikirim</option>
                    <option value="selesai">Selesai</option>
                    <option value="batal">Dibatalkan</option>
                </select>
            </div>
            <div class="form-grp">
                <label class="form-lbl">Catatan Admin (opsional)</label>
                <textarea name="catatan_admin" class="form-inp" rows="2" placeholder="Keterangan perubahan status..."></textarea>
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">Simpan</button>
                <button type="button" onclick="tutupModal()" class="btn btn-secondary" style="flex:1;justify-content:center">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleStatusModal(id, statusSaatIni) {
    const modal = document.getElementById('statusModal');
    document.getElementById('statusForm').action = `/admin/pesanan/${id}/status`;
    document.getElementById('statusSelect').value = statusSaatIni;
    modal.style.display = 'flex';
}
function tutupModal() {
    document.getElementById('statusModal').style.display = 'none';
}
document.getElementById('statusModal')?.addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});
</script>
@endpush