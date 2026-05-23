@extends('layouts.admin')
@section('title', 'Pengiriman Armada')
@section('page_title', 'Pengiriman Armada')
@section('breadcrumb', 'Layanan › Pengiriman')

@section('content')

{{-- Stats --}}
<div class="adm-stat-strip" style="grid-template-columns:repeat(2,1fr);max-width:360px">
    <div class="adm-card" style="padding:16px 20px">
        <div class="adm-stat-val" style="color:var(--terracotta)">{{ $siapKirim }}</div>
        <div class="adm-stat-lbl" style="margin-top:4px">🚚 Siap Dikirim</div>
    </div>
    <div class="adm-card" style="padding:16px 20px">
        <div class="adm-stat-val" style="color:#2563eb">{{ $dikirim }}</div>
        <div class="adm-stat-lbl" style="margin-top:4px">📦 Dalam Pengiriman</div>
    </div>
</div>

<div class="adm-toolbar" style="justify-content:space-between">
    <form method="GET" style="display:flex;gap:8px">
        <input class="form-inp" type="text" name="q" value="{{ request('q') }}"
               placeholder="No. pesanan, penerima, kota..."
               style="width:260px;font-size:13px;padding:8px 12px">
        <select class="form-inp" name="status" style="width:140px;font-size:13px;padding:8px">
            <option value="">Semua Status</option>
            <option value="dikonfirmasi" {{ request('status')==='dikonfirmasi'?'selected':'' }}>Dikonfirmasi</option>
            <option value="diproses"     {{ request('status')==='diproses'    ?'selected':'' }}>Diproses</option>
            <option value="dikirim"      {{ request('status')==='dikirim'     ?'selected':'' }}>Dikirim</option>
        </select>
        <button class="btn btn-secondary" style="font-size:13px;padding:8px 14px">Filter</button>
    </form>
    <a href="{{ route('admin.pengiriman.ongkir') }}" class="btn btn-secondary" style="font-size:13px;padding:8px 16px">
        ⚙️ Kelola Zona Ongkir
    </a>
</div>

<div class="adm-tbl-wrap">
    <table class="data-tbl">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Penerima</th>
                <th>Kota Tujuan</th>
                <th style="text-align:center">Status</th>
                <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $pesanan)
            <tr style="border-top:1px solid rgba(176,139,110,.06)">
                <td style="padding:12px 16px">
                    <div style="font-weight:600;color:var(--soil);font-size:13px">{{ $pesanan->nomor_pesanan }}</div>
                    <div style="font-size:11.5px;color:var(--clay)">{{ $pesanan->created_at?->format('d M Y') }}</div>
                </td>
                <td style="padding:12px 16px">
                    <div style="font-weight:600;color:var(--soil)">{{ $pesanan->penerima }}</div>
                    <div style="font-size:12px;color:var(--clay)">{{ $pesanan->telepon_penerima }}</div>
                </td>
                <td style="padding:12px 16px;color:var(--clay)">
                    {{ $pesanan->kota_tujuan }}@if($pesanan->provinsi_tujuan), {{ $pesanan->provinsi_tujuan }}@endif
                </td>
                <td style="padding:12px 16px;text-align:center">
                    @php
                        $sPill = match($pesanan->status) {
                            'dikonfirmasi' => 's-proses',
                            'diproses'     => 's-proses',
                            'dikirim'      => 's-lunas',
                            default        => 's-pending',
                        };
                    @endphp
                    <span class="status-pill {{ $sPill }}">{{ ucfirst($pesanan->status) }}</span>
                </td>
                <td style="padding:12px 16px;text-align:center">
                    <a href="{{ route('admin.pengiriman.show', $pesanan) }}" class="act-btn" title="Detail" style="display:inline-flex">
                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:40px;text-align:center;color:var(--clay)">
                    Tidak ada pesanan pengiriman armada aktif.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:14px 20px">{{ $pesanans->links() }}</div>
</div>

@endsection
