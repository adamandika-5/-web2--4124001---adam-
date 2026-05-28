@extends('layouts.app')

@section('title', 'Beranda — Sinar Alam')
@section('meta_desc', 'Sinar Alam menyediakan material bangunan berkualitas seperti semen, pasir, batu, besi, keramik, cat, pipa, dan sewa alat bangunan untuk kebutuhan renovasi dan konstruksi di Jombang.')

@section('content')

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="grain"></div>
    <div class="hero-pad">
        <div class="hero-eyebrow" style="display:inline-flex;margin-bottom:22px">
            <div class="hero-eyebrow-dot"></div>
            Toko Material Terpercaya — Jombang, Jawa Timur
        </div>
        <h1 class="hero-headline" style="font-size:clamp(32px,5vw,62px)">
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
<section class="home-section">
    <div style="text-align:center;margin-bottom:32px">
        <div class="section-label">Belanja Berdasarkan Kategori</div>
        <h2 class="section-title">Kategori <em>Produk</em></h2>
    </div>

    <div class="home-cats-grid">
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
<section class="home-cta-section">
    <div class="grain"></div>
    <div class="home-cta-glow"></div>
    <div class="home-cta-inner">
        <div class="section-label" style="color:var(--clay-light);margin-bottom:8px">Layanan Kami</div>
        <h2 class="section-title light" style="margin-bottom:14px">
            Butuh Material <em>Bangunan?</em>
        </h2>
        <p style="font-size:15px;color:rgba(232,220,199,.55);margin-bottom:32px;max-width:480px;margin-left:auto;margin-right:auto;line-height:1.7">
            Hubungi kami sekarang untuk pemesanan dan konsultasi material bangunan gratis!
        </p>
        <div class="home-cta-btns">
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