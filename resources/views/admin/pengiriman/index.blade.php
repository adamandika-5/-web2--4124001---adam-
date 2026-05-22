@extends('layouts.admin')
@section('title', 'Pengiriman Armada')
@section('page_title', 'Pengiriman Armada')
@section('breadcrumb', 'Layanan › Pengiriman')

@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:22px;max-width:400px">
    <div style="background:#fff;border-radius:var(--r-md);padding:16px 20px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)">
        <div style="font-size:24px;font-weight:700;color:var(--terracotta);font-family:var(--fd)">{{ $siapKirim }}</div>
        <div style="font-size:12.5px;color:var(--clay)">🚚 Siap Dikirim</div>
    </div>
    <div style="background:#fff;border-radius:var(--r-md);padding:16px 20px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)">
        <div style="font-size:24px;font-weight:700;color:#2563eb;font-family:var(--fd)">{{ $dikirim }}</div>
        <div style="font-size:12.5px;color:var(--clay)">📦 Dalam Pengiriman</div>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <form method="GET" style="display:flex;gap:8px">
        <input class="form-inp" type="text" name="q" value="{{ request('q') }}"
               placeholder="No. pesanan, penerima, kota..."
               style="width:260px;padding:8px 12px;font-size:13px">
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

<div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07);overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px">
        <thead>
            <tr style="background:var(--oat)">
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">No. Pesanan</th>
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">Penerima</th>
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">Kota Tujuan</th>
                <th style="padding:12px 16px;text-align:center;color:var(--clay);font-weight:600">Status</th>
                <th style="padding:12px 16px;text-align:center;color:var(--clay);font-weight:600">Aksi</th>
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
                        $warna = match($pesanan->status) {
                            'dikonfirmasi' => ['rgba(37,99,235,.1)', '#2563eb'],
                            'diproses'     => ['rgba(234,179,8,.12)', '#a16207'],
                            'dikirim'      => ['rgba(22,163,74,.1)', '#16a34a'],
                            default        => ['rgba(176,139,110,.1)', 'var(--clay)'],
                        };
                    @endphp
                    <span style="padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:700;
                        background:{{ $warna[0] }};color:{{ $warna[1] }}">
                        {{ ucfirst($pesanan->status) }}
                    </span>
                </td>
                <td style="padding:12px 16px;text-align:center">
                    <a href="{{ route('admin.pengiriman.show', $pesanan) }}"
                       style="font-size:12.5px;color:var(--terracotta);font-weight:600;text-decoration:none">
                        Detail
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
