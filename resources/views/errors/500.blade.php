@extends('layouts.app')
@section('title', '500 — Server Error')

@section('content')
<div style="max-width:560px;margin:80px auto;padding:0 24px;text-align:center">
    <div style="width:88px;height:88px;background:rgba(192,142,58,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:40px;margin:0 auto 20px;border:2px solid rgba(192,142,58,.2)">
        ⚙️
    </div>
    <div style="font-family:var(--fd);font-size:80px;font-weight:700;color:var(--sand);line-height:1;margin-bottom:4px">500</div>
    <div style="font-family:var(--fd);font-size:24px;font-weight:500;color:var(--soil);margin-bottom:10px">
        Terjadi Kesalahan Server
    </div>
    <p style="font-size:14.5px;color:var(--clay);line-height:1.75;margin-bottom:28px">
        Maaf, terjadi kesalahan di server kami.<br>
        Tim teknis sudah diberitahu. Coba lagi beberapa saat.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('beranda') }}" class="btn btn-primary">🏠 Kembali ke Beranda</a>
        <a href="javascript:location.reload()" class="btn btn-secondary">🔄 Coba Lagi</a>
    </div>
    <div style="margin-top:28px;font-size:12.5px;color:var(--clay)">
        Butuh bantuan? Hubungi kami di
        <a href="https://wa.me/{{ config('app.whatsapp_number','6281234567890') }}"
           style="color:var(--terracotta);font-weight:700;text-decoration:none">WhatsApp</a>
    </div>
</div>
@endsection