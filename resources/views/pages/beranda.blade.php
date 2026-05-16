@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_desc', 'Sinar Alam — Toko material bangunan terlengkap di Pasuruan, Jawa Timur. Semen, besi, keramik, cat, dan sewa alat bangunan dengan harga terbaik.')

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
<section style="padding:52px 48px;max-width:1280px;margin:0 auto">
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
<section style="padding:0 48px 64px;max-width:1280px;margin:0 auto">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px">
        <div>
            <div class="section-label">Pilihan Terlaris</div>
            <h2 class="section-title">Produk <em>Unggulan</em></h2>
        </div>
        <a href="{{ route('katalog.index', ['filter' => 'unggulan']) }}" class="btn btn-secondary btn-sm">Lihat Semua →</a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px">
        @foreach($produkUnggulan as $produk)
            <div class="prod-card">
                <a href="{{ route('produk.show', $produk->slug) }}" style="text-decoration:none;color:inherit">
                    <div class="prod-img" style="background:{{ $produk->warna_bg ?? '#F4EDE0' }}">
                        @if($produk->gambar_utama)
                            <img src="{{ asset('storage/'.$produk->gambar_utama) }}" alt="{{ $produk->nama }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <span style="font-size:52px">{{ $produk->ikon ?? '📦' }}</span>
                        @endif
                        <div class="prod-img-badge">
                            @if($produk->harga_promo)
                                <span class="badge badge-sale">Promo</span>
                            @elseif($produk->created_at->diffInDays() < 14)
                                <span class="badge badge-new">Baru</span>
                            @endif
                        </div>
                        <form action="{{ route('wishlist.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <button type="submit" class="prod-wish">
                                {{ auth()->check() && auth()->user()->wishlist->contains($produk->id) ? '❤️' : '♡' }}
                            </button>
                        </form>
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
<section style="padding:0 48px 64px;max-width:1280px;margin:0 auto">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px">
        <div>
            <div class="section-label">Penawaran Terbatas</div>
            <h2 class="section-title">Promo & <em>Diskon</em> Minggu Ini</h2>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px">
        @php $promoUtama = $promos->first(); $promoKecil = $promos->slice(1, 2); @endphp
        <div style="background:var(--soil-mid);border-radius:var(--r-lg);padding:36px 40px;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:flex-end;min-height:200px">
            <div class="grain"></div>
            <div style="position:absolute;top:-20%;right:-5%;width:55%;font-size:120px;opacity:.1;pointer-events:none">🏗️</div>
            <div style="position:relative;z-index:2">
                <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(232,220,199,.5);margin-bottom:8px">{{ $promoUtama->label ?? 'Promo Spesial' }}</div>
                <div style="font-family:var(--fd);font-size:clamp(20px,2.5vw,30px);color:var(--sand);font-weight:500;line-height:1.2;margin-bottom:14px">
                    {!! $promoUtama->judul_html ?? $promoUtama->nama !!}
                </div>
                <a href="{{ route('promo.show', $promoUtama->slug) }}" class="btn" style="background:rgba(255,255,255,.1);color:#fff;border:1.5px solid rgba(255,255,255,.25);font-size:13px;padding:9px 20px">Belanja Sekarang →</a>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:16px">
            @foreach($promoKecil as $p)
                <a href="{{ route('promo.show', $p->slug) }}" style="background:#2E3A1C;border-radius:var(--r-lg);padding:22px 28px;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:flex-end;min-height:90px;text-decoration:none">
                    <div class="grain"></div>
                    <div style="position:relative;z-index:2">
                        <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(232,220,199,.5);margin-bottom:6px">{{ $p->label ?? 'Promo' }}</div>
                        <div style="font-family:var(--fd);font-size:18px;color:var(--sand);font-weight:500">{!! $p->judul_html ?? $p->nama !!}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── PRODUK TERBARU ── --}}
<section style="padding:0 48px 64px;max-width:1280px;margin:0 auto">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px">
        <div>
            <div class="section-label">Baru Masuk</div>
            <h2 class="section-title">Produk <em>Terbaru</em></h2>
        </div>
        <a href="{{ route('katalog.index', ['sort' => 'terbaru']) }}" class="btn btn-secondary btn-sm">Lihat Semua →</a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px">
        @foreach($produkTerbaru as $produk)
            <div class="prod-card">
                <a href="{{ route('produk.show', $produk->slug) }}" style="text-decoration:none;color:inherit">
                    <div class="prod-img" style="background:{{ $produk->warna_bg ?? '#F4EDE0' }}">
                        @if($produk->gambar_utama)
                            <img src="{{ asset('storage/'.$produk->gambar_utama) }}" alt="{{ $produk->nama }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <span style="font-size:52px">{{ $produk->ikon ?? '📦' }}</span>
                        @endif
                        <div class="prod-img-badge"><span class="badge badge-new">Baru</span></div>
                        <form action="{{ route('wishlist.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <button type="submit" class="prod-wish">♡</button>
                        </form>
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
<section style="background:var(--soil);padding:72px 48px;position:relative;overflow:hidden">
    <div class="grain"></div>
    <div style="max-width:1280px;margin:0 auto;position:relative;z-index:2;display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;background-image:radial-gradient(ellipse 60% 80% at 75% 40%,rgba(198,107,61,.18) 0%,transparent 55%)">
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
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
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
                <div style="font-size:13px;color:rgba(232,220,199,.42);margin-top:7px;line-height:1.4">Pengiriman area Pasuruan & sekitarnya</div>
            </div>
        </div>
    </div>
</section>

@endsection