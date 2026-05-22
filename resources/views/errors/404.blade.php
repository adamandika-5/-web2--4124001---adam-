@extends('layouts.app')
@section('title', '404 — Halaman Tidak Ditemukan')

@section('content')
<div style="max-width:600px;margin:80px auto;padding:0 24px;text-align:center">
    <div style="font-family:var(--fd);font-size:120px;font-weight:700;color:var(--sand);line-height:1;margin-bottom:8px">
        404
    </div>
    <div style="font-family:var(--fd);font-size:26px;font-weight:500;color:var(--soil);margin-bottom:12px">
        Halaman tidak ditemukan
    </div>
    <p style="font-size:14.5px;color:var(--clay);line-height:1.75;margin-bottom:32px">
        Halaman yang kamu cari tidak ada atau sudah dipindahkan.<br>
        Coba kembali ke beranda atau cari produk yang kamu butuhkan.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('beranda') }}" class="btn btn-primary">🏠 Kembali ke Beranda</a>
        <a href="{{ route('katalog.index') }}" class="btn btn-secondary">📦 Lihat Katalog</a>
    </div>
</div>
@endsection