@extends('layouts.app')
@section('title', 'Pesanan Berhasil!')

@section('content')
<div style="max-width:680px;margin:64px auto;padding:0 24px">
    <div style="background:#fff;border-radius:var(--r-xl);padding:52px 48px;box-shadow:var(--sh-lg);border:1px solid rgba(176,139,110,.1);text-align:center">
        <div style="width:88px;height:88px;background:rgba(96,108,56,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:42px;margin:0 auto 24px;border:2px solid rgba(96,108,56,.2)">✅</div>
        <h1 style="font-family:var(--fd);font-size:30px;font-weight:500;color:var(--soil);margin-bottom:8px">Pesanan Berhasil Dibuat!</h1>
        <p style="font-size:14.5px;color:var(--clay);margin-bottom:28px">Terima kasih, <strong>{{ auth()->user()->name }}</strong>. Pesanan Anda sedang menunggu konfirmasi.</p>
        <div style="background:linear-gradient(135deg,var(--soil),var(--soil-mid));border-radius:var(--r-lg);padding:22px 24px;margin-bottom:24px;position:relative;overflow:hidden">
            <div class="grain"></div>
            <div style="position:relative;z-index:2">
                <div style="font-size:11.5px;color:rgba(232,220,199,.5);font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px">Nomor Pesanan Anda</div>
                <div style="font-family:var(--fd);font-size:24px;font-weight:700;color:var(--sand);letter-spacing:.04em">{{ $pesanan->nomor_pesanan }}</div>
                <div style="font-size:12px;color:rgba(232,220,199,.4);margin-top:6px">Simpan nomor ini untuk melacak pesanan Anda</div>
            </div>
        </div>
        <div style="background:var(--oat);border-radius:var(--r-lg);padding:18px 20px;margin-bottom:28px;text-align:left">
            <div style="display:flex;flex-direction:column;gap:9px">
                <div style="display:flex;justify-content:space-between;font-size:13.5px"><span style="color:var(--clay)">Total Pembayaran</span><span style="font-weight:700;color:var(--soil)">Rp {{ number_format($pesanan->total,0,',','.') }}</span></div>
                <div style="display:flex;justify-content:space-between;font-size:13.5px"><span style="color:var(--clay)">Dikirim ke</span><span style="font-weight:700;color:var(--soil)">{{ $pesanan->kota_tujuan }}</span></div>
                <div style="display:flex;justify-content:space-between;font-size:13.5px"><span style="color:var(--clay)">Jenis Kirim</span><span style="font-weight:700;color:var(--soil)">{{ $pesanan->jenis_pengiriman==='armada'?'🚛 Armada Sendiri':'📦 Ekspedisi' }}</span></div>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('pesanan.show', $pesanan->nomor_pesanan) }}" class="btn btn-primary" style="flex:1;justify-content:center;padding:13px">Lihat Detail Pesanan →</a>
            <a href="{{ route('beranda') }}" class="btn btn-secondary" style="flex:1;justify-content:center">Lanjut Belanja</a>
        </div>
        <a href="https://wa.me/{{ config('app.whatsapp_number') }}" target="_blank" style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:14px;font-size:13px;color:var(--moss);font-weight:600;text-decoration:none">💬 Hubungi kami via WhatsApp</a>
    </div>
</div>
@endsection