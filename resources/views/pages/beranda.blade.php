@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_desc', 'Sinar Alam menyediakan material bangunan berkualitas seperti semen, pasir, batu, besi, keramik, cat, pipa, dan sewa alat bangunan untuk kebutuhan renovasi dan konstruksi di Jombang.')

@section('content')

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="grain"></div>
    <div class="hero-content">
        <div>
            <div class="hero-eyebrow">
                <div class="hero-eyebrow-dot"></div>
                Toko Material Terpercaya — Jawa Timur
            </div>
            <h1 class="hero-headline">
                Semua Material,<br>
                <em>Satu Tempat.</em>
            </h1>
            <p class="hero-sub">
                Semen, besi, keramik, cat, hingga alat bangunan. Pengiriman cepat ke seluruh Jawa Timur dengan armada sendiri. Harga terbaik, kualitas terjamin.
            </p>
            <form action="{{ route('katalog.index') }}" method="GET" class="hero-search-bar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(232,220,199,.45)" stroke-width="2" style="flex-shrink:0;margin-left:8px">
                    <circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/>
                </svg>
                <input type="text" name="q" placeholder="Mau cari material apa hari ini?">
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            </form>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">{{ number_format($totalProduk) }}+</div>
                    <div class="hero-stat-lbl">Produk Tersedia</div>
                </div>
                <div style="width:1px;background:rgba(232,220,199,.12)"></div>
                <div>
                    <div class="hero-stat-num">{{ number_format($totalPesanan) }}+</div>
                    <div class="hero-stat-lbl">Pesanan Terkirim</div>
                </div>
                <div style="width:1px;background:rgba(232,220,199,.12)"></div>
                <div>
                    <div class="hero-stat-num">38 Kab.</div>
                    <div class="hero-stat-lbl">Area Pengiriman</div>
                </div>
            </div>
        </div>
        <div>
            <div class="h-float">
                <div class="h-float-icon" style="background:rgba(198,107,61,.2)">🚚</div>
                <div>
                    <div class="h-float-title">Pengiriman Armada Sendiri</div>
                    <div class="h-float-sub">Pasir, semen massal, batu bata — langsung ke lokasi proyek</div>
                </div>
            </div>
            <div class="h-float">
                <div class="h-float-icon" style="background:rgba(192,142,58,.2)">🔧</div>
                <div>
                    <div class="h-float-title">Sewa Alat Bangunan</div>
                    <div class="h-float-sub">Scaffolding, concrete mixer — harga harian & mingguan</div>
                </div>
            </div>
            <div class="h-float">
                <div class="h-float-icon" style="background:rgba(96,108,56,.2)">📋</div>
                <div>
                    <div class="h-float-title">Konsultasi Material Gratis</div>
                    <div class="h-float-sub">Tim kami siap bantu estimasi kebutuhan proyek Anda</div>
                </div>
            </div>
            <div class="h-float">
                <div class="h-float-icon" style="background:rgba(123,155,174,.2)">🏦</div>
                <div>
                    <div class="h-float-title">Bayar DP untuk Proyek Besar</div>
                    <div class="h-float-sub">Tersedia opsi pembayaran bertahap — QRIS, Transfer, COD</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── KATEGORI ── --}}
<section class="home-section">
    <div class="section-label">Belanja Berdasarkan Kategori</div>
    <h2 class="section-title">Material apa yang <em>Anda butuhkan?</em></h2>

    <div class="cats-grid">
        @foreach($kategoris as $index => $kat)
            <a href="{{ route('katalog.index', ['kategori' => $kat->slug]) }}" class="cat-chip c{{ ($index % 8) + 1 }}">
                <div class="cat-icon">{{ $kat->ikon ?? '📦' }}</div>
                <div class="cat-name">{{ $kat->nama }}</div>
            </a>
        @endforeach
    </div>
</section>

{{-- ── PRODUK UNGGULAN ── --}}
<section class="home-section" style="padding-top:0">
    <div class="home-sec-hdr">
        <div>
            <div class="section-label">Pilihan Terlaris</div>
            <h2 class="section-title">Produk <em>Unggulan</em></h2>
        </div>
        <a href="{{ route('katalog.index', ['filter' => 'unggulan']) }}" class="btn btn-secondary btn-sm">Lihat Semua →</a>
    </div>

    <div class="home-prod-grid">
        @foreach($produkUnggulan as $produk)
            <div class="prod-card">
                <form action="{{ route('wishlist.toggle') }}" method="POST" style="display:inline;z-index:10">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <button type="submit" class="prod-wish">
                        {{ auth()->check() && auth()->user()->wishlist->contains($produk->id) ? '❤️' : '♡' }}
                    </button>
                </form>
                <a href="{{ route('produk.show', $produk->slug) }}" style="text-decoration:none;color:inherit">
                    <div class="prod-img">
                        @if($produk->gambar_utama)
                            <img src="{{ asset('storage/'.$produk->gambar_utama) }}" alt="{{ $produk->nama }}" onerror="this.onerror=null; this.src='{{ asset('gambar/placeholder.svg') }}';">
                        @else
                            <img src="{{ asset('gambar/placeholder.svg') }}" alt="{{ $produk->nama }}">
                        @endif
                        <div class="prod-img-badge">
                            @if($produk->harga_promo)
                                <span class="badge badge-sale">Promo</span>
                            @elseif($produk->created_at->diffInDays() < 14)
                                <span class="badge badge-new">Baru</span>
                            @endif
                        </div>
                    </div>
                    <div class="prod-body">
                        <div class="prod-cat">{{ $produk->kategori->nama }}</div>
                        <div class="prod-name">{{ $produk->nama }}</div>
                        <div class="prod-price-row">
                            <span class="prod-price">Rp {{ number_format($produk->harga_promo ?? $produk->harga, 0, ',', '.') }}</span>
                            <span class="prod-unit">/{{ $produk->satuan }}</span>
                            @if($produk->harga_promo)
                                <span class="prod-old">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @if($produk->stok > 20)
                            <div class="prod-stock-ok">✓ Stok: {{ number_format($produk->stok) }} {{ $produk->satuan }}</div>
                        @elseif($produk->stok > 0)
                            <div class="prod-stock-low">⚠ Stok: {{ $produk->stok }} {{ $produk->satuan }}</div>
                        @else
                            <div style="font-size:11px;color:#c03030;margin-top:4px;font-weight:600">✕ Stok Habis</div>
                        @endif
                    </div>
                </a>
                <div class="prod-footer">
                    @if($produk->stok > 0)
                        <form action="{{ route('keranjang.tambah') }}" method="POST" style="flex:1">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">+ Keranjang</button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-sm" style="flex:1;justify-content:center;opacity:.5;cursor:not-allowed" disabled>Stok Habis</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── PROMO BANNER ── --}}
@if($promos->count() > 0)
<section class="home-section pb-4">
    <div class="home-sec-hdr">
        <div>
            <div class="section-label">Penawaran Terbatas</div>
            <h2 class="section-title">Promo & <em>Diskon</em> Minggu Ini</h2>
        </div>
    </div>
    <div class="home-promo-grid">
        @php $promoUtama = $promos->first(); $promoKecil = $promos->slice(1, 2); @endphp
        <div class="promo-card-main">
            <div class="grain"></div>
            <div class="promo-icon">🏗️</div>
            <div class="promo-content">
                <div class="promo-label">{{ $promoUtama->label ?? 'Promo Spesial' }}</div>
                <div class="promo-title">
                    {!! $promoUtama->judul_html ?? $promoUtama->nama !!}
                </div>
                <a href="{{ route('promo.show', $promoUtama->slug) }}" class="btn btn-light-outline">Belanja Sekarang →</a>
            </div>
        </div>
        <div class="promo-grid-sub">
            @foreach($promoKecil as $p)
                <a href="{{ route('promo.show', $p->slug) }}" class="promo-card-sub">
                    <div class="grain"></div>
                    <div class="promo-content">
                        <div class="promo-label">{{ $p->label ?? 'Promo' }}</div>
                        <div class="promo-title">{!! $p->judul_html ?? $p->nama !!}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── PRODUK TERBARU ── --}}
<section class="home-section pt-0">
    <div class="home-sec-hdr">
        <div>
            <div class="section-label">Baru Masuk</div>
            <h2 class="section-title">Produk <em>Terbaru</em></h2>
        </div>
        <a href="{{ route('katalog.index', ['sort' => 'terbaru']) }}" class="btn btn-secondary btn-sm">Lihat Semua →</a>
    </div>
    <div class="home-prod-grid">
        @foreach($produkTerbaru as $produk)
            <div class="prod-card">
                <form action="{{ route('wishlist.toggle') }}" method="POST" style="display:inline;z-index:10">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <button type="submit" class="prod-wish">
                        {{ auth()->check() && auth()->user()->wishlist->contains($produk->id) ? '❤️' : '♡' }}
                    </button>
                </form>
                <a href="{{ route('produk.show', $produk->slug) }}" style="text-decoration:none;color:inherit">
                    <div class="prod-img">
                        @if($produk->gambar_utama)
                            <img src="{{ asset('storage/'.$produk->gambar_utama) }}" alt="{{ $produk->nama }}" onerror="this.onerror=null; this.src='{{ asset('gambar/placeholder.svg') }}';">
                        @else
                            <img src="{{ asset('gambar/placeholder.svg') }}" alt="{{ $produk->nama }}">
                        @endif
                        <div class="prod-img-badge"><span class="badge badge-new">Baru</span></div>
                    </div>
                    <div class="prod-body">
                        <div class="prod-cat">{{ $produk->kategori->nama }}</div>
                        <div class="prod-name">{{ $produk->nama }}</div>
                        <div class="prod-price-row">
                            <span class="prod-price">Rp {{ number_format($produk->harga_promo ?? $produk->harga, 0, ',', '.') }}</span>
                            <span class="prod-unit">/{{ $produk->satuan }}</span>
                        </div>
                        @if($produk->stok > 0)
                            <div class="prod-stock-ok">✓ Stok: {{ number_format($produk->stok) }} {{ $produk->satuan }}</div>
                        @endif
                    </div>
                </a>
                <div class="prod-footer">
                    @if($produk->stok > 0)
                        <form action="{{ route('keranjang.tambah') }}" method="POST" style="flex:1">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">+ Keranjang</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── TRUST SECTION ── --}}
<section class="home-trust-section">
    <div class="grain"></div>
    <div class="home-trust-inner">
        <div>
            <div class="section-label" style="color:var(--clay-light)">Kenapa Pilih Kami</div>
            <h2 class="section-title light">Lebih dari sekadar<br><em>toko bangunan.</em></h2>
            <div style="display:flex;flex-direction:column;gap:22px;margin-top:32px">
                <div style="display:flex;gap:16px;align-items:flex-start">
                    <div style="width:44px;height:44px;background:rgba(198,107,61,.2);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">🚚</div>
                    <div>
                        <div style="font-size:15px;font-weight:600;color:var(--sand);margin-bottom:3px">Pengiriman Armada Sendiri</div>
                        <div style="font-size:13px;color:rgba(232,220,199,.48);line-height:1.65">Material berat dikirim menggunakan truk armada sendiri khusus Jawa Timur.</div>
                    </div>
                </div>
                <div style="display:flex;gap:16px;align-items:flex-start">
                    <div style="width:44px;height:44px;background:rgba(192,142,58,.2);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">💰</div>
                    <div>
                        <div style="font-size:15px;font-weight:600;color:var(--sand);margin-bottom:3px">Harga Transparan</div>
                        <div style="font-size:13px;color:rgba(232,220,199,.48);line-height:1.65">Estimasi ongkir otomatis sebelum checkout. Harga selalu up-to-date.</div>
                    </div>
                </div>
                <div style="display:flex;gap:16px;align-items:flex-start">
                    <div style="width:44px;height:44px;background:rgba(96,108,56,.2);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">📞</div>
                    <div>
                        <div style="font-size:15px;font-weight:600;color:var(--sand);margin-bottom:3px">Konsultasi Material via WhatsApp</div>
                        <div style="font-size:13px;color:rgba(232,220,199,.48);line-height:1.65">Tim kami siap bantu kalkulasi kebutuhan material proyek Anda, gratis.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-stats-grid">
            <div style="background:rgba(255,255,255,.05);border:1px solid rgba(232,220,199,.09);border-radius:var(--r-lg);padding:28px 22px">
                <div style="font-family:var(--fd);font-size:44px;font-weight:700;color:var(--terracotta);line-height:1">{{ number_format($totalPesanan) }}+</div>
                <div style="font-size:13px;color:rgba(232,220,199,.42);margin-top:7px;line-height:1.4">Pesanan terkirim sejak 2019</div>
            </div>
            <div style="background:rgba(255,255,255,.05);border:1px solid rgba(232,220,199,.09);border-radius:var(--r-lg);padding:28px 22px">
                <div style="font-family:var(--fd);font-size:44px;font-weight:700;color:var(--terracotta);line-height:1">4,8</div>
                <div style="font-size:13px;color:rgba(232,220,199,.42);margin-top:7px;line-height:1.4">Rating dari ribuan ulasan pelanggan</div>
            </div>
            <div style="background:rgba(255,255,255,.05);border:1px solid rgba(232,220,199,.09);border-radius:var(--r-lg);padding:28px 22px">
                <div style="font-family:var(--fd);font-size:44px;font-weight:700;color:var(--terracotta);line-height:1">38</div>
                <div style="font-size:13px;color:rgba(232,220,199,.42);margin-top:7px;line-height:1.4">Kab/Kota Jawa Timur dijangkau armada</div>
            </div>
            <div style="background:rgba(255,255,255,.05);border:1px solid rgba(232,220,199,.09);border-radius:var(--r-lg);padding:28px 22px">
                <div style="font-family:var(--fd);font-size:44px;font-weight:700;color:var(--terracotta);line-height:1">1 Hr</div>
                <div style="font-size:13px;color:rgba(232,220,199,.42);margin-top:7px;line-height:1.4">Pengiriman area Jombang & sekitarnya</div>
            </div>
        </div>
    </div>
</section>

@endsection