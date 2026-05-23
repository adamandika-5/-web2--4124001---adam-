@extends('layouts.app')

@section('title', 'Beranda — Sinar Alam')
@section('meta_desc', 'Sinar Alam — Toko material bangunan terlengkap di Pasuruan, Jawa Timur. Semen, besi, keramik, cat, dan sewa alat bangunan.')

@section('content')

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="grain"></div>
    <div style="position:relative;z-index:2;max-width:760px;margin:0 auto;padding:80px 48px;text-align:center">
        <div class="hero-eyebrow" style="display:inline-flex;margin-bottom:22px">
            <div class="hero-eyebrow-dot"></div>
            Toko Material Terpercaya — Pasuruan, Jawa Timur
        </div>
        <h1 class="hero-headline" style="font-size:clamp(38px,5vw,62px)">
            Toko Bangunan<br><em>Terlengkap.</em>
        </h1>
        <p class="hero-sub" style="margin:0 auto 32px;max-width:480px;text-align:center">
            Menyediakan berbagai kebutuhan material bangunan berkualitas dengan harga terjangkau.
            Pengiriman cepat ke seluruh Jawa Timur.
        </p>
        <a href="{{ route('katalog.index') }}" class="btn btn-primary" style="font-size:15px;padding:13px 32px">
            Lihat Katalog Produk →
        </a>
    </div>
</section>

{{-- ── KATEGORI ── --}}
<section style="padding:56px 48px;max-width:1280px;margin:0 auto">
    <div style="text-align:center;margin-bottom:32px">
        <div class="section-label">Belanja Berdasarkan Kategori</div>
        <h2 class="section-title">Kategori <em>Produk</em></h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px">
        <a href="{{ route('katalog.index', ['q' => 'semen']) }}" class="cat-card">
            <div class="cat-card-icon">🪨</div>
            <div>
                <div class="cat-card-name">Semen</div>
                <div class="cat-card-desc">Berbagai merek semen berkualitas</div>
            </div>
        </a>
        <a href="{{ route('katalog.index', ['q' => 'pasir']) }}" class="cat-card">
            <div class="cat-card-icon">🏗️</div>
            <div>
                <div class="cat-card-name">Pasir &amp; Batu</div>
                <div class="cat-card-desc">Material dasar konstruksi</div>
            </div>
        </a>
        <a href="{{ route('katalog.index', ['q' => 'cat']) }}" class="cat-card">
            <div class="cat-card-icon">🎨</div>
            <div>
                <div class="cat-card-name">Cat</div>
                <div class="cat-card-desc">Cat tembok &amp; kayu berbagai warna</div>
            </div>
        </a>
        <a href="{{ route('katalog.index', ['q' => 'alat']) }}" class="cat-card">
            <div class="cat-card-icon">🔧</div>
            <div>
                <div class="cat-card-name">Alat Bangunan</div>
                <div class="cat-card-desc">Peralatan tukang lengkap</div>
            </div>
        </a>
    </div>
</section>

{{-- ── CTA ── --}}
<section style="background:var(--soil);padding:72px 48px;position:relative;overflow:hidden">
    <div class="grain"></div>
    <div style="background-image:radial-gradient(ellipse 60% 80% at 60% 50%,rgba(198,107,61,.22) 0%,transparent 65%);position:absolute;inset:0;pointer-events:none"></div>
    <div style="max-width:1280px;margin:0 auto;text-align:center;position:relative;z-index:2">
        <div class="section-label" style="color:var(--clay-light);margin-bottom:8px">Layanan Kami</div>
        <h2 class="section-title light" style="margin-bottom:14px">
            Butuh Material <em>Bangunan?</em>
        </h2>
        <p style="font-size:15px;color:rgba(232,220,199,.55);margin-bottom:32px;max-width:480px;margin-left:auto;margin-right:auto;line-height:1.7">
            Hubungi kami sekarang untuk pemesanan dan konsultasi material bangunan gratis!
        </p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
            <a href="{{ route('katalog.index') }}" class="btn btn-primary" style="font-size:15px;padding:13px 28px">
                Lihat Katalog →
            </a>
            <a href="https://wa.me/{{ config('app.whatsapp_number', '625749503756') }}"
               target="_blank" rel="noopener"
               class="btn" style="background:rgba(255,255,255,.1);color:var(--sand);border:1.5px solid rgba(232,220,199,.25);font-size:15px;padding:13px 28px">
                💬 Hubungi via WhatsApp
            </a>
        </div>
    </div>
</section>

@endsection