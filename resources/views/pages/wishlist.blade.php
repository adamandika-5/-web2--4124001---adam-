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
        @php
            // Ambil data gambar produk secara aman
            $gambarData = null;
            if ($produk->relationLoaded('gambar')) {
                $gambarData = $produk->getRelation('gambar');
            }
            
            if (!$gambarData || (method_exists($gambarData, 'isEmpty') && $gambarData->isEmpty())) {
                if (isset($produk->attributes['gambar'])) {
                    $gambarData = $produk->attributes['gambar'];
                } elseif (isset($produk->gambar)) {
                    $gambarData = $produk->gambar;
                }
            }

            $path = null;
            if ($gambarData instanceof \Illuminate\Database\Eloquent\Collection || $gambarData instanceof \Illuminate\Support\Collection) {
                if (method_exists($gambarData, 'firstWhere')) {
                    $utama = $gambarData->firstWhere('is_utama', true) ?? $gambarData->first();
                    $path = $utama?->path ?? null;
                } else {
                    $path = $gambarData->first()?->path ?? null;
                }
            } elseif (is_array($gambarData)) {
                $first = collect($gambarData)->first();
                $path = is_array($first) ? ($first['path'] ?? null) : (is_object($first) ? ($first->path ?? null) : $first);
            } elseif (is_string($gambarData) && !empty($gambarData)) {
                $decoded = json_decode($gambarData, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $firstVal = collect($decoded)->first();
                    if (is_array($firstVal)) {
                        $path = $firstVal['path'] ?? null;
                    } elseif (is_object($firstVal)) {
                        $path = $firstVal->path ?? null;
                    } else {
                        $path = $firstVal;
                    }
                } else {
                    $path = $gambarData;
                }
            } elseif (is_object($gambarData)) {
                $path = $gambarData->path ?? null;
            }

            if (!$path || $path === '{}' || $path === '[]') {
                $rawCol = $produk->getAttributes()['gambar'] ?? null;
                if ($rawCol && is_string($rawCol) && $rawCol !== $gambarData) {
                    $decoded = json_decode($rawCol, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $firstVal = collect($decoded)->first();
                        if (is_array($firstVal)) {
                            $path = $firstVal['path'] ?? null;
                        } else {
                            $path = $firstVal;
                        }
                    } else {
                        $path = $rawCol;
                    }
                }
            }

            if ($path === '{}' || $path === '[]' || empty($path)) {
                $path = null;
            }

            $imageUrl = null;
            if ($path) {
                $path = trim($path);
                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    $imageUrl = $path;
                } elseif (str_starts_with($path, 'storage/')) {
                    $imageUrl = asset($path);
                } elseif (str_starts_with($path, 'gambar/')) {
                    $imageUrl = asset($path);
                } else {
                    $imageUrl = asset('storage/' . $path);
                }
            } else {
                $imageUrl = asset('gambar/placeholder.svg');
            }
        @endphp
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
                    <img src="{{ $imageUrl }}"
                         alt="{{ $produk->nama }}"
                         onerror="this.onerror=null; this.src='{{ asset('gambar/placeholder.svg') }}';">
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
            @php
                // Ambil data gambar produk secara aman
                $gambarData2 = null;
                if ($p->relationLoaded('gambar')) {
                    $gambarData2 = $p->getRelation('gambar');
                }
                
                if (!$gambarData2 || (method_exists($gambarData2, 'isEmpty') && $gambarData2->isEmpty())) {
                    if (isset($p->attributes['gambar'])) {
                        $gambarData2 = $p->attributes['gambar'];
                    } elseif (isset($p->gambar)) {
                        $gambarData2 = $p->gambar;
                    }
                }

                $path2 = null;
                if ($gambarData2 instanceof \Illuminate\Database\Eloquent\Collection || $gambarData2 instanceof \Illuminate\Support\Collection) {
                    if (method_exists($gambarData2, 'firstWhere')) {
                        $utama2 = $gambarData2->firstWhere('is_utama', true) ?? $gambarData2->first();
                        $path2 = $utama2?->path ?? null;
                    } else {
                        $path2 = $gambarData2->first()?->path ?? null;
                    }
                } elseif (is_array($gambarData2)) {
                    $first2 = collect($gambarData2)->first();
                    $path2 = is_array($first2) ? ($first2['path'] ?? null) : (is_object($first2) ? ($first2->path ?? null) : $first2);
                } elseif (is_string($gambarData2) && !empty($gambarData2)) {
                    $decoded2 = json_decode($gambarData2, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                        $firstVal2 = collect($decoded2)->first();
                        if (is_array($firstVal2)) {
                            $path2 = $firstVal2['path'] ?? null;
                        } elseif (is_object($firstVal2)) {
                            $path2 = $firstVal2->path ?? null;
                        } else {
                            $path2 = $firstVal2;
                        }
                    } else {
                        $path2 = $gambarData2;
                    }
                } elseif (is_object($gambarData2)) {
                    $path2 = $gambarData2->path ?? null;
                }

                if (!$path2 || $path2 === '{}' || $path2 === '[]') {
                    $rawCol2 = $p->getAttributes()['gambar'] ?? null;
                    if ($rawCol2 && is_string($rawCol2) && $rawCol2 !== $gambarData2) {
                        $decoded2 = json_decode($rawCol2, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                            $firstVal2 = collect($decoded2)->first();
                            if (is_array($firstVal2)) {
                                $path2 = $firstVal2['path'] ?? null;
                            } else {
                                $path2 = $firstVal2;
                            }
                        } else {
                            $path2 = $rawCol2;
                        }
                    }
                }

                if ($path2 === '{}' || $path2 === '[]' || empty($path2)) {
                    $path2 = null;
                }

                $imageUrl2 = null;
                if ($path2) {
                    $path2 = trim($path2);
                    if (str_starts_with($path2, 'http://') || str_starts_with($path2, 'https://')) {
                        $imageUrl2 = $path2;
                    } elseif (str_starts_with($path2, 'storage/')) {
                        $imageUrl2 = asset($path2);
                    } elseif (str_starts_with($path2, 'gambar/')) {
                        $imageUrl2 = asset($path2);
                    } else {
                        $imageUrl2 = asset('storage/' . $path2);
                    }
                } else {
                    $imageUrl2 = asset('gambar/placeholder.svg');
                }
            @endphp
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
                        <img src="{{ $imageUrl2 }}"
                             alt="{{ $p->nama }}"
                             onerror="this.onerror=null; this.src='{{ asset('gambar/placeholder.svg') }}';">
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