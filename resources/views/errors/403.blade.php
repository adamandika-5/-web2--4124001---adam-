@extends('layouts.app')
@section('title', '403 — Akses Ditolak')

@section('content')
<div style="max-width:560px;margin:80px auto;padding:0 24px;text-align:center">
    <div style="width:88px;height:88px;background:rgba(192,48,48,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:40px;margin:0 auto 20px;border:2px solid rgba(192,48,48,.2)">
        🔒
    </div>
    <div style="font-family:var(--fd);font-size:80px;font-weight:700;color:var(--sand);line-height:1;margin-bottom:4px">403</div>
    <div style="font-family:var(--fd);font-size:24px;font-weight:500;color:var(--soil);margin-bottom:10px">
        Akses Ditolak
    </div>
    <p style="font-size:14.5px;color:var(--clay);line-height:1.75;margin-bottom:28px">
        Kamu tidak memiliki izin untuk mengakses halaman ini.<br>
        Pastikan kamu sudah login dengan akun yang sesuai.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('beranda') }}" class="btn btn-primary">🏠 Kembali ke Beranda</a>
        @guest
            <a href="{{ route('login') }}" class="btn btn-secondary">🔐 Login</a>
        @else
            <a href="javascript:history.back()" class="btn btn-secondary">← Kembali</a>
        @endguest
    </div>
</div>
@endsection