@extends('layouts.admin')
@section('title', 'Detail Pengiriman')
@section('page_title', 'Detail Pengiriman')
@section('breadcrumb', 'Layanan › Pengiriman › ' . $pesanan->nomor_pesanan)

@section('content')
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
    {{-- KOLOM KIRI --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        {{-- Info pesanan --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--sand)">
                <div>
                    <div style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">Nomor Pesanan</div>
                    <div style="font-family:var(--fd);font-size:22px;font-weight:700;color:var(--soil)">{{ $pesanan->nomor_pesanan }}</div>
                    <div style="font-size:13px;color:var(--clay);margin-top:4px">{{ $pesanan->created_at?->format('d M Y H:i') }}</div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px">
                    @php
                        $warna = match($pesanan->status) {
                            'dikonfirmasi' => ['rgba(37,99,235,.1)', '#2563eb'],
                            'diproses'     => ['rgba(234,179,8,.12)', '#a16207'],
                            'dikirim'      => ['rgba(22,163,74,.1)', '#16a34a'],
                            'selesai'      => ['rgba(22,163,74,.1)', '#16a34a'],
                            default        => ['rgba(176,139,110,.1)', 'var(--clay)'],
                        };
                    @endphp
                    <span style="padding:5px 14px;border-radius:99px;font-size:13px;font-weight:700;
                        background:{{ $warna[0] }};color:{{ $warna[1] }}">
                        {{ ucfirst($pesanan->status) }}
                    </span>
                </div>
            </div>

            {{-- Form update status pengiriman --}}
            <form action="{{ route('admin.pengiriman.update', $pesanan) }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap">
                @csrf
                @method('PUT')
                <select name="status" class="form-inp" style="flex:1;padding:8px 12px;font-size:13px">
                    <option value="diproses" {{ $pesanan->status==='diproses'?'selected':'' }}>Diproses</option>
                    <option value="dikirim" {{ $pesanan->status==='dikirim'?'selected':'' }}>Dikirim (Dalam Pengiriman)</option>
                    <option value="selesai" {{ $pesanan->status==='selesai'?'selected':'' }}>Selesai</option>
                </select>
                <input type="text" name="catatan_admin" class="form-inp" placeholder="Catatan Admin (opsional)" style="flex:2;padding:8px 12px;font-size:13px">
                <button type="submit" class="btn btn-primary btn-sm">Perbarui Status</button>
            </form>
        </div>

        {{-- Daftar produk --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Produk Dipesan
            </div>
            @foreach($pesanan->items as $item)
            <div style="display:flex;gap:16px;align-items:center;padding:14px 0;border-bottom:1px solid rgba(176,139,110,.07)">
                <div style="width:54px;height:54px;background:var(--oat);border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;overflow:hidden">
                    📦
                </div>
                <div style="flex:1">
                    <div style="font-weight:700;font-size:13.5px;color:var(--soil)">{{ $item->nama_produk }}</div>
                    <div style="font-size:12px;color:var(--clay);margin-top:3px">
                        {{ $item->qty }} × Rp {{ number_format($item->harga_satuan,0,',','.') }}
                    </div>
                </div>
                <div style="font-size:15px;font-weight:700;color:var(--soil)">
                    Rp {{ number_format($item->subtotal,0,',','.') }}
                </div>
            </div>
            @endforeach

            <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;justify-content:space-between;font-size:13.5px">
                    <span style="color:var(--clay)">Subtotal</span>
                    <span>Rp {{ number_format($pesanan->subtotal,0,',','.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:2px solid var(--sand)">
                    <span style="font-size:15px;font-weight:700;color:var(--soil)">Total</span>
                    <span style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--terracotta)">
                        Rp {{ number_format($pesanan->total,0,',','.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        {{-- Pelanggan --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Pelanggan
            </div>
            <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:4px">{{ $pesanan->user->name ?? '—' }}</div>
            <div style="font-size:12.5px;color:var(--clay)">{{ $pesanan->user->email ?? '—' }}</div>
        </div>

        {{-- Alamat Pengiriman --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Alamat Pengiriman
            </div>
            <div style="font-size:13.5px;font-weight:700;color:var(--soil);margin-bottom:4px">{{ $pesanan->penerima }}</div>
            <div style="font-size:13px;color:var(--clay);margin-bottom:4px">📞 {{ $pesanan->telepon_penerima }}</div>
            <div style="font-size:13px;color:var(--soil-light);line-height:1.6">
                {{ $pesanan->alamat_pengiriman }},<br>
                {{ $pesanan->kota_tujuan }}, {{ $pesanan->provinsi_tujuan }}
            </div>
        </div>

        {{-- Back button --}}
        <div>
            <a href="{{ route('admin.pengiriman.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center;text-decoration:none">
                ← Kembali ke Daftar Pengiriman
            </a>
        </div>
    </div>
</div>
@endsection
