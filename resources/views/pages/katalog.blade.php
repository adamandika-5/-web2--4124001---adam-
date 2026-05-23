@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('meta_desc', 'Temukan produk material bangunan seperti semen, besi, keramik, cat, dan kebutuhan proyek lainnya.')

@section('content')
@php
    $produkList = $produk ?? collect();
    $kategoriList = $kategoris ?? collect();

    $totalProduk = method_exists($produkList, 'total') ? $produkList->total() : $produkList->count();
    $firstItem = method_exists($produkList, 'firstItem') ? ($produkList->firstItem() ?? 0) : ($totalProduk > 0 ? 1 : 0);
    $lastItem = method_exists($produkList, 'lastItem') ? ($produkList->lastItem() ?? 0) : $totalProduk;
@endphp

<div style="max-width:1280px;margin:0 auto;padding:32px 48px 64px">
    <div style="margin-bottom:28px">
        <div class="section-label">Belanja Material</div>

        <h1 class="section-title" style="font-size:clamp(26px,3vw,38px)">
            Katalog
            @if(request('q'))
                — Hasil untuk <em>"{{ request('q') }}"</em>
            @elseif(request('kategori'))
                <em>{{ optional($kategoriList->firstWhere('slug', request('kategori')))->nama ?? request('kategori') }}</em>
            @else
                <em>Produk</em>
            @endif
        </h1>

        <div style="font-size:13.5px;color:var(--clay);margin-top:6px">
            Menampilkan {{ $firstItem }}–{{ $lastItem }} dari {{ number_format($totalProduk) }} produk
        </div>
    </div>

    <div style="display:grid;grid-template-columns:240px 1fr;gap:28px;align-items:start">
        <aside style="position:sticky;top:80px">
            <form method="GET" action="{{ route('katalog.index') }}">
                <div class="filter-card">
                    <span class="filter-title">Cari Produk</span>
                    <div class="filter-search-row">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Nama produk"
                               class="form-inp">
                        <button type="submit" class="btn btn-primary btn-sm" style="flex-shrink:0;padding:8px 12px">Cari</button>
                    </div>
                </div>

                @if($kategoriList->count() > 0)
                    <div class="filter-card">
                        <span class="filter-title">Kategori</span>
                        <div style="display:flex;flex-direction:column;gap:2px">
                            <a href="{{ route('katalog.index', request()->except('kategori', 'page')) }}"
                               class="filter-cat-link {{ !request('kategori') ? 'active' : '' }}">
                                Semua Kategori
                            </a>
                            @foreach($kategoriList as $kat)
                                <a href="{{ route('katalog.index', array_merge(request()->except('kategori', 'page'), ['kategori' => $kat->slug])) }}"
                                   class="filter-cat-link {{ request('kategori') === $kat->slug ? 'active' : '' }}">
                                    <span>{{ $kat->nama }}</span>
                                    <span class="filter-cat-count">{{ $kat->produk_count ?? 0 }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="filter-card">
                    <span class="filter-title">Rentang Harga</span>
                    <div class="filter-price-grid">
                        <div>
                            <span class="filter-price-lbl">Min (Rp)</span>
                            <input type="number" name="harga_min"
                                   value="{{ request('harga_min') }}"
                                   placeholder="0" min="0"
                                   class="form-inp">
                        </div>
                        <div>
                            <span class="filter-price-lbl">Max (Rp)</span>
                            <input type="number" name="harga_max"
                                   value="{{ request('harga_max') }}"
                                   placeholder="Maks" min="0"
                                   class="form-inp">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:8px">
                    Terapkan Filter
                </button>

                @if(request()->hasAny(['q','kategori','harga_min','harga_max','filter','satuan','sort']))
                    <a href="{{ route('katalog.index') }}" class="btn btn-secondary"
                       style="width:100%;justify-content:center;text-decoration:none">
                        Reset Filter
                    </a>
                @endif
            </form>
        </aside>

        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
                <div style="font-size:13px;color:var(--clay)">
                    @if(request()->hasAny(['q','kategori','harga_min','harga_max','filter']))
                        <span style="padding:3px 10px;background:rgba(198,107,61,.1);color:var(--terracotta);border-radius:99px;font-size:12px;font-weight:700">
                            Filter aktif
                        </span>
                    @endif
                </div>

                <form method="GET" action="{{ route('katalog.index') }}" style="display:flex;align-items:center;gap:8px">
                    @foreach(request()->except('sort', 'page') as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach

                    <label style="font-size:13px;color:var(--clay)">Urutkan:</label>

                    <select name="sort"
                            onchange="this.form.submit()"
                            class="form-inp" style="width:auto;padding:7px 10px;font-size:13px;cursor:pointer">
                        <option value="terlaris" {{ request('sort','terlaris') === 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                        <option value="terbaru" {{ request('sort') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                        <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                        <option value="nama_asc" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </form>
            </div>

            @if($produkList->isEmpty())
                <div style="text-align:center;padding:72px 32px;background:#fff;border-radius:var(--r-lg);border:1px dashed rgba(176,139,110,.2)">
                    <div style="font-family:var(--fd);font-size:20px;color:var(--soil);margin-bottom:8px">
                        Produk tidak ditemukan
                    </div>

                    <div style="font-size:13.5px;color:var(--clay);margin-bottom:24px">
                        Coba kata kunci berbeda atau hapus filter yang aktif.
                    </div>

                    <a href="{{ route('katalog.index') }}" class="btn btn-secondary">
                        Reset Filter
                    </a>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px">
                    @foreach($produkList as $p)
                        @php
                            $gambarPath = null;
                            $gambarData = null;

                            if ($p->relationLoaded('gambar')) {
                                $gambarData = $p->getRelation('gambar');
                            } elseif (method_exists($p, 'gambar')) {
                                $gambarData = $p->gambar;
                            } elseif (isset($p->gambar)) {
                                $gambarData = $p->gambar;
                            }

                            if (is_string($gambarData)) {
                                $gambarPath = $gambarData;
                            } elseif (is_object($gambarData) && method_exists($gambarData, 'first')) {
                                $gambarPertama = $gambarData->first();
                                $gambarPath = $gambarPertama->path ?? null;
                            } elseif (is_array($gambarData)) {
                                $gambarPertama = collect($gambarData)->first();
                                $gambarPath = is_array($gambarPertama)
                                    ? ($gambarPertama['path'] ?? null)
                                    : ($gambarPertama->path ?? null);
                            } elseif (is_object($gambarData)) {
                                $gambarPath = $gambarData->path ?? null;
                            }
                        @endphp

                        <div class="prod-card">
                            <a href="{{ route('produk.show', $p->slug) }}" style="text-decoration:none;color:inherit">
                                <div class="prod-img" style="background:{{ $p->warna_bg ?? '#F4EDE0' }}">
                                    @if($gambarPath)
                                        <img src="{{ asset('storage/' . $gambarPath) }}"
                                             alt="{{ $p->nama }}"
                                             style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        <span style="font-size:48px">{{ $p->ikon ?? 'Produk' }}</span>
                                    @endif

                                    <div class="prod-img-badge">
                                        @if($p->harga_promo)
                                            <span class="badge badge-sale">Promo</span>
                                        @elseif($p->unggulan)
                                            <span class="badge badge-new">Unggulan</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="prod-body">
                                    <div class="prod-cat">{{ optional($p->kategori)->nama ?? 'Tanpa kategori' }}</div>

                                    <div class="prod-name">{{ $p->nama }}</div>

                                    <div class="prod-price-row">
                                        <span class="prod-price">
                                            Rp {{ number_format((float)($p->harga_promo ?? $p->harga ?? 0), 0, ',', '.') }}
                                        </span>

                                        @if($p->satuan)
                                            <span class="prod-unit">/{{ $p->satuan }}</span>
                                        @endif

                                        @if($p->harga_promo)
                                            <span class="prod-old">
                                                Rp {{ number_format((float)$p->harga, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if(($p->stok ?? 0) > 20)
                                        <div class="prod-stock-ok">
                                            Stok: {{ number_format($p->stok) }} {{ $p->satuan }}
                                        </div>
                                    @elseif(($p->stok ?? 0) > 0)
                                        <div class="prod-stock-low">
                                            Sisa: {{ $p->stok }} {{ $p->satuan }}
                                        </div>
                                    @else
                                        <div style="font-size:11px;color:#c03030;margin-top:4px;font-weight:600">
                                            Stok Habis
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="prod-footer">
                                @if(($p->stok ?? 0) > 0)
                                    @auth
                                        <form action="{{ route('keranjang.tambah') }}" method="POST" style="flex:1">
                                            @csrf
                                            <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                            <input type="hidden" name="qty" value="1">

                                            <button type="submit"
                                                    class="btn btn-primary btn-sm"
                                                    style="width:100%;justify-content:center">
                                                Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="btn btn-primary btn-sm"
                                           style="flex:1;justify-content:center;text-align:center;text-decoration:none">
                                            Masuk untuk Beli
                                        </a>
                                    @endauth
                                @else
                                    <button class="btn btn-secondary btn-sm"
                                            style="flex:1;justify-content:center;opacity:.5;cursor:not-allowed"
                                            disabled>
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(method_exists($produkList, 'links'))
                    <div style="margin-top:32px;display:flex;justify-content:center">
                        {{ $produkList->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection