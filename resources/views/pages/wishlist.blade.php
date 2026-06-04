@extends('layouts.app')
@section('title', 'Wishlist Saya')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:36px 48px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px">
        <div>
            <div class="section-label">Koleksi Saya</div>
            <h1 class="section-title">Wishlist <em>Favorit</em></h1>
        </div>
        @if($wishlist->isNotEmpty())
        <div style="font-size:13.5px;color:var(--clay)">
            <strong style="color:var(--soil)">{{ $wishlist->count() }}</strong> produk tersimpan
        </div>
        @endif
    </div>

    @if($wishlist->isEmpty())
    {{-- Empty state --}}
    <div style="text-align:center;padding:80px 40px;background:#fff;border-radius:var(--r-xl);border:1px solid rgba(176,139,110,.1)">
        <div style="font-size:64px;margin-bottom:16px">❤️</div>
        <div style="font-family:var(--fs);font-size:22px;font-weight:700;color:var(--soil);margin-bottom:8px">Wishlist masih kosong</div>
        <div style="font-size:14px;color:var(--clay);margin-bottom:24px">Simpan produk favorit Anda agar mudah ditemukan kembali</div>
        <a href="{{ route('katalog.index') }}" class="btn btn-primary">Jelajahi Katalog →</a>
    </div>

    @else
    {{--
      Eager loading di controller:
      auth()->user()->wishlist()
          ->with(['kategori', 'gambar' => fn($q) => $q->where('is_utama', true)])
          ->get()
    --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px">
        @foreach($wishlist as $produk)
        <div class="prod-card" style="position:relative">

            {{-- Hapus dari wishlist --}}
            <form action="{{ route('wishlist.toggle') }}" method="POST"
                  style="position:absolute;top:10px;right:10px;z-index:10">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                <button type="submit"
                        style="width:30px;height:30px;background:#fff;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:15px;box-shadow:var(--sh-sm);transition:all .2s;color:var(--terracotta)"
                        title="Hapus dari wishlist"
                        onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    ❤️
                </button>
            </form>

            <a href="{{ route('produk.show', $produk->slug) }}" style="text-decoration:none;color:inherit">
                <div class="prod-img">
                    @include('partials.produk-img', ['produk' => $produk])
                    @if($produk->harga_promo)
                        <div class="prod-img-badge">
                            <span class="badge badge-sale">−{{ $produk->diskon_persen }}%</span>
                        </div>
                    @endif
                </div>
                <div class="prod-body">
                    <div class="prod-cat">{{ $produk->kategori->nama }}</div>
                    <div class="prod-name">{{ $produk->nama }}</div>
                    <div class="prod-price-row">
                        <span class="prod-price">
                            Rp {{ number_format($produk->harga_final, 0, ',', '.') }}
                        </span>
                        <span class="prod-unit">/{{ $produk->satuan }}</span>
                        @if($produk->harga_promo)
                            <span class="prod-old">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    @if($produk->stok > 20)
                        <div class="prod-stock-ok">✓ Stok: {{ number_format($produk->stok) }} {{ $produk->satuan }}</div>
                    @elseif($produk->stok > 0)
                        <div class="prod-stock-low">⚠ Sisa {{ $produk->stok }} {{ $produk->satuan }}</div>
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
                        <button type="submit"
                                class="btn btn-primary btn-sm"
                                style="width:100%;justify-content:center">
                            🛒 Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-sm"
                            style="flex:1;justify-content:center;opacity:.5;cursor:not-allowed" disabled>
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Rekomendasi --}}
    @if($rekomendasi->isNotEmpty())
    <div style="margin-top:56px">
        <div class="section-label">Mungkin Kamu Suka</div>
        <h2 class="section-title" style="margin-bottom:24px">Produk <em>Rekomendasi</em></h2>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px">
            @foreach($rekomendasi as $p)
            <div class="prod-card">
                <form action="{{ route('wishlist.toggle') }}" method="POST" style="display:inline;z-index:10">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $p->id }}">
                    <button type="submit" class="prod-wish">
                        {{ auth()->check() && auth()->user()->wishlist->contains($p->id) ? '❤️' : '♡' }}
                    </button>
                </form>
                <a href="{{ route('produk.show', $p->slug) }}" style="text-decoration:none;color:inherit">
                    <div class="prod-img">
                        @include('partials.produk-img', ['produk' => $p])
                    </div>
                    <div class="prod-body">
                        <div class="prod-cat">{{ $p->kategori->nama }}</div>
                        <div class="prod-name">{{ $p->nama }}</div>
                        <div class="prod-price-row">
                            <span class="prod-price">Rp {{ number_format($p->harga_final, 0, ',', '.') }}</span>
                            <span class="prod-unit">/{{ $p->satuan }}</span>
                        </div>
                        <div class="{{ $p->stok > 20 ? 'prod-stock-ok' : 'prod-stock-low' }}">
                            {{ $p->stok > 0 ? '✓ Tersedia' : '✕ Habis' }}
                        </div>
                    </div>
                </a>
                <div class="prod-footer">
                    @if($p->stok > 0)
                    <form action="{{ route('keranjang.tambah') }}" method="POST" style="flex:1">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $p->id }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">
                            + Keranjang
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif
</div>
@endsection