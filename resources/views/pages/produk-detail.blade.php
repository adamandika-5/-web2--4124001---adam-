@extends('layouts.app')

@section('title', $produk->nama)
@section('meta_desc', Str::limit(strip_tags($produk->deskripsi), 160))

@section('content')

{{-- Breadcrumb --}}
<div style="max-width:1280px;margin:0 auto;padding:20px 48px 0">
    <nav style="font-size:13px;color:var(--clay);display:flex;align-items:center;gap:7px;flex-wrap:wrap">
        <a href="{{ route('beranda') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Beranda</a>
        <span>›</span>
        <a href="{{ route('katalog.index') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Katalog</a>
        <span>›</span>
        <a href="{{ route('katalog.index', ['kategori' => [$produk->kategori->slug]]) }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">
            {{ $produk->kategori->nama }}
        </a>
        <span>›</span>
        <span>{{ Str::limit($produk->nama, 40) }}</span>
    </nav>
</div>

<div style="max-width:1280px;margin:0 auto;padding:28px 48px 64px">

    {{-- ── Normalisasi gambar produk ── --}}
    @php
        // Coba ambil dari relasi yang sudah eager-loaded dulu
        $gambarRaw = null;
        if ($produk->relationLoaded('gambar')) {
            $rel = $produk->getRelation('gambar');
            if ($rel instanceof \Illuminate\Support\Collection) {
                $gambarRaw = $rel;
            }
        }
        // Fallback ke kolom string/JSON jika relasi tidak tersedia
        if ($gambarRaw === null) {
            $gambarRaw = $produk->attributes['gambar'] ?? null;
        }

        // Normalisasi ke array string path
        if ($gambarRaw instanceof \Illuminate\Support\Collection) {
            $gambarList = $gambarRaw->map(fn($g) => is_object($g) ? ($g->path ?? null) : (string)$g)
                                    ->filter(fn($v) => !empty($v))
                                    ->values()
                                    ->toArray();
        } elseif (is_string($gambarRaw) && $gambarRaw !== '') {
            $decoded = json_decode($gambarRaw, true);
            $gambarList = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                ? array_values(array_filter($decoded))
                : [$gambarRaw];
        } elseif (is_array($gambarRaw)) {
            $gambarList = array_values(array_filter(
                array_map(fn($g) => is_object($g) ? ($g->path ?? null) : $g, $gambarRaw)
            ));
        } else {
            $gambarList = [];
        }

        $gambarUtama = $gambarList[0] ?? null;
    @endphp

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:start">

        {{-- ── GALERI ── --}}
        <div>
            {{-- Gambar utama --}}
            <div style="background:linear-gradient(135deg,#F5EFE6,#EDE4D5);border-radius:var(--r-xl);aspect-ratio:1;display:flex;align-items:center;justify-content:center;border:1.5px solid rgba(176,139,110,.12);box-shadow:var(--sh-md);margin-bottom:14px;position:relative;overflow:hidden" id="mainImgWrap">
                @if($gambarUtama)
                    <img id="mainImg" src="{{ asset('storage/' . $gambarUtama) }}"
                         alt="{{ $produk->nama }}"
                         style="width:100%;height:100%;object-fit:cover;transition:opacity .25s">
                @else
                    <span style="font-size:120px">{{ $produk->ikon ?? '📦' }}</span>
                @endif

                {{-- Badge --}}
                <div style="position:absolute;top:14px;left:14px;display:flex;gap:6px">
                    @if($produk->harga_promo)
                        <span class="badge badge-sale" style="font-size:12px;padding:5px 12px">−{{ $produk->diskon_persen }}%</span>
                    @endif
                    @if($produk->unggulan)
                        <span class="badge badge-new" style="font-size:12px;padding:5px 12px">⭐ Unggulan</span>
                    @endif
                </div>
            </div>

            {{-- Thumbnail strip --}}
            @if(count($gambarList) > 1)
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px">
                @foreach($gambarList as $i => $gambarPath)
                    <div onclick="gantiGambar('{{ asset('storage/' . $gambarPath) }}', this)"
                         style="background:#F5EFE6;border-radius:var(--r-md);aspect-ratio:1;cursor:pointer;border:2.5px solid {{ $i===0 ? 'var(--terracotta)' : 'rgba(176,139,110,.2)' }};overflow:hidden;transition:border-color .2s"
                         class="thumb-item">
                        <img src="{{ asset('storage/' . $gambarPath) }}" alt="{{ $produk->nama }} foto {{ $i+1 }}"
                             style="width:100%;height:100%;object-fit:cover">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── INFO PRODUK ── --}}
        <div>
            {{-- Badges status --}}
            <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap">
                @if($produk->harga_promo)
                    <span class="badge badge-sale">Promo Aktif</span>
                @endif
                @if($produk->stok_status === 'rendah')
                    <span class="badge badge-low">⚠ Stok Terbatas</span>
                @elseif($produk->stok_status === 'habis')
                    <span class="badge" style="background:rgba(192,48,48,.1);color:#c03030">✕ Stok Habis</span>
                @endif
            </div>

            <h1 style="font-family:var(--fs);font-size:clamp(22px,2.5vw,30px);font-weight:700;color:var(--soil);line-height:1.2;margin-bottom:10px">
                {{ $produk->nama }}
            </h1>

            <div style="font-size:13px;color:var(--clay);margin-bottom:18px;display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <span>SKU: <strong>{{ $produk->sku ?? '—' }}</strong></span>
                <span>·</span>
                <a href="{{ route('katalog.index', ['kategori' => [$produk->kategori->slug]]) }}"
                   style="color:var(--terracotta);text-decoration:none;font-weight:600">
                    {{ $produk->kategori->nama }}
                </a>
                @if($produk->subKategori)
                    <span>›</span>
                    <span>{{ $produk->subKategori->nama }}</span>
                @endif
            </div>

            {{-- Harga --}}
            <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:6px;flex-wrap:wrap">
                <div style="font-family:var(--fd);font-size:36px;font-weight:700;color:var(--terracotta)">
                    Rp {{ number_format($produk->harga_final, 0, ',', '.') }}
                </div>
                <div style="font-size:15px;color:var(--clay)">/ {{ $produk->satuan }}</div>
                @if($produk->harga_promo)
                    <div style="font-size:14px;color:var(--concrete);text-decoration:line-through">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>
                @endif
            </div>

            <div style="font-size:13px;font-weight:600;margin-bottom:24px">
                @if($produk->stok_status === 'tersedia')
                    <span style="color:var(--moss)">✓ Stok: {{ number_format($produk->stok) }} {{ $produk->satuan }}</span>
                    @if($produk->harga_promo)
                        <span style="color:var(--clay);margin-left:8px">· Hemat Rp {{ number_format($produk->harga - $produk->harga_promo, 0, ',', '.') }}/{{ $produk->satuan }}</span>
                    @endif
                @elseif($produk->stok_status === 'rendah')
                    <span style="color:var(--ochre)">⚠ Sisa {{ $produk->stok }} {{ $produk->satuan }} — segera order!</span>
                @else
                    <span style="color:#c03030">✕ Stok habis saat ini</span>
                @endif
            </div>

            {{-- Estimasi Pengiriman --}}
            <div style="background:var(--oat);border-radius:var(--r-lg);padding:18px;margin-bottom:22px;border:1px solid rgba(176,139,110,.12)">
                <div style="font-size:12.5px;font-weight:700;color:var(--soil);margin-bottom:12px">🚚 Estimasi Pengiriman</div>
                <div style="display:flex;flex-direction:column;gap:8px">
                    @if(in_array($produk->jenis_pengiriman, ['armada','keduanya']))
                    <div style="display:flex;align-items:center;justify-content:space-between;font-size:13px">
                        <span style="color:var(--clay)">🚛 Armada Sendiri (Jawa Timur)</span>
                        <span style="font-weight:700;color:var(--soil)">Rp 25.000 – 350.000</span>
                    </div>
                    @endif
                    @if(in_array($produk->jenis_pengiriman, ['ekspedisi','keduanya']))
                    <div style="display:flex;align-items:center;justify-content:space-between;font-size:13px">
                        <span style="color:var(--clay)">📦 J&T / JNE / SiCepat</span>
                        <span style="font-weight:700;color:var(--soil)">Tergantung berat & kota</span>
                    </div>
                    @endif
                    <div style="font-size:11.5px;color:var(--concrete);padding-top:6px;border-top:1px solid rgba(176,139,110,.12)">
                        Estimasi ongkir otomatis dihitung saat checkout
                    </div>
                </div>
            </div>

            {{-- Qty & Aksi --}}
            @if($produk->stok > 0)
            <form action="{{ route('keranjang.tambah') }}" method="POST" id="formTambah">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                <div style="display:flex;gap:12px;align-items:center;margin-bottom:14px">
                    <div style="font-size:13px;font-weight:700;color:var(--soil)">Jumlah</div>
                    <div style="display:flex;align-items:center;border:1.5px solid var(--sand);border-radius:var(--r-md);overflow:hidden">
                        <button type="button" onclick="ubahQty(-1)"
                                style="width:38px;height:38px;border:none;background:var(--oat);cursor:pointer;font-size:18px;color:var(--clay);font-weight:700;transition:background .2s"
                                onmouseover="this.style.background='var(--sand)'" onmouseout="this.style.background='var(--oat)'">−</button>
                        <input type="number" name="qty" id="qtyInput" value="1" min="1" max="{{ $produk->stok }}"
                               style="width:54px;border:none;text-align:center;font-family:var(--fb);font-size:14px;font-weight:700;color:var(--soil);background:#fff;outline:none;padding:8px 0">
                        <button type="button" onclick="ubahQty(1)"
                                style="width:38px;height:38px;border:none;background:var(--oat);cursor:pointer;font-size:18px;color:var(--clay);font-weight:700;transition:background .2s"
                                onmouseover="this.style.background='var(--sand)'" onmouseout="this.style.background='var(--oat)'">+</button>
                    </div>
                    <div style="font-size:13px;color:var(--clay)">
                        = <strong id="totalHarga" style="color:var(--terracotta)">
                            Rp {{ number_format($produk->harga_final, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
            </form>

            <div style="display:flex;gap:10px;margin-bottom:16px">
                <button type="submit" form="formTambah" class="btn btn-primary" style="flex:2;justify-content:center;padding:14px;font-size:15px">
                    🛒 Tambah ke Keranjang
                </button>
                <a href="{{ route('checkout.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center;align-items:center;display:flex">
                    ⚡ Beli Sekarang
                </a>
                <form action="{{ route('wishlist.toggle') }}" method="POST" style="flex-shrink:0">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <button type="submit" class="btn btn-secondary" style="width:48px;height:50px;padding:0;justify-content:center;border-radius:var(--r-md);font-size:18px">
                        @auth
                            {{ auth()->user()->wishlist->contains($produk->id) ? '❤️' : '♡' }}
                        @else
                            ♡
                        @endauth
                    </button>
                </form>
            </div>
            @endif

            {{-- WA Konsultasi --}}
            <a href="https://wa.me/{{ config('app.whatsapp_number') }}?text=Halo%20Sinar%20Alam%2C%20saya%20ingin%20tanya%20soal%20{{ urlencode($produk->nama) }}"
               target="_blank"
               style="display:flex;align-items:center;gap:10px;padding:13px 18px;background:#F0FDF4;border:1.5px solid rgba(37,211,102,.3);border-radius:var(--r-md);text-decoration:none;font-size:13.5px;font-weight:600;color:#166534;transition:all .2s"
               onmouseover="this.style.background='#DCFCE7'" onmouseout="this.style.background='#F0FDF4'">
                <span style="font-size:20px">💬</span>
                Tanya stok, harga proyek besar, atau konsultasi material via WhatsApp
            </a>
        </div>
    </div>

    {{-- ── TAB DESKRIPSI ── --}}
    <div style="margin-top:56px">
        <div style="display:flex;gap:0;border-bottom:2px solid var(--sand);margin-bottom:28px" id="tabNav">
            <button onclick="gantiTab('deskripsi',this)" class="tab-btn active" style="padding:11px 24px;border:none;background:transparent;cursor:pointer;font-family:var(--fb);font-size:14px;font-weight:700;color:var(--terracotta);border-bottom:2px solid var(--terracotta);margin-bottom:-2px;transition:all .2s">Deskripsi</button>
            <button onclick="gantiTab('spesifikasi',this)" class="tab-btn" style="padding:11px 24px;border:none;background:transparent;cursor:pointer;font-family:var(--fb);font-size:14px;font-weight:600;color:var(--clay);transition:all .2s">Spesifikasi</button>
        </div>

        {{-- Deskripsi --}}
        <div id="tab-deskripsi" style="display:grid;grid-template-columns:2fr 1fr;gap:40px">
            <div>
                <div style="font-size:14.5px;line-height:1.85;color:var(--soil-light)">
                    {!! nl2br(e($produk->deskripsi)) !!}
                </div>
                @if($produk->supplier)
                <div style="margin-top:20px;padding:14px 18px;background:var(--oat);border-radius:var(--r-md);border:1px solid rgba(176,139,110,.12)">
                    <div style="font-size:12px;font-weight:700;color:var(--clay);margin-bottom:4px">Supplier / Distributor</div>
                    <div style="font-size:13.5px;font-weight:600;color:var(--soil)">{{ $produk->supplier->nama }}</div>
                    @if($produk->supplier->kota)
                        <div style="font-size:12px;color:var(--clay)">📍 {{ $produk->supplier->kota }}</div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Spesifikasi ringkas --}}
            @if($produk->spesifikasi)
            <div style="background:#fff;border-radius:var(--r-lg);padding:22px;border:1px solid rgba(176,139,110,.1);box-shadow:var(--sh-sm)">
                <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--sand)">
                    Spesifikasi Teknis
                </div>
                <div style="display:flex;flex-direction:column">
                    @foreach(explode("\n", $produk->spesifikasi) as $spec)
                        @php $parts = explode(':', $spec, 2); @endphp
                        @if(count($parts) === 2)
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(176,139,110,.08);font-size:13px">
                            <span style="color:var(--clay)">{{ trim($parts[0]) }}</span>
                            <span style="font-weight:600;color:var(--soil)">{{ trim($parts[1]) }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Spesifikasi Tab --}}
        <div id="tab-spesifikasi" style="display:none">
            @if($produk->spesifikasi)
            <div style="background:#fff;border-radius:var(--r-lg);padding:24px;border:1px solid rgba(176,139,110,.1);box-shadow:var(--sh-sm);max-width:600px">
                @foreach(explode("\n", $produk->spesifikasi) as $spec)
                    @php $parts = explode(':', $spec, 2); @endphp
                    @if(count($parts) === 2)
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(176,139,110,.08);font-size:14px">
                        <span style="color:var(--clay);font-weight:600">{{ trim($parts[0]) }}</span>
                        <span style="font-weight:700;color:var(--soil)">{{ trim($parts[1]) }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
            @else
                <p style="color:var(--clay);font-size:14px">Spesifikasi belum tersedia.</p>
            @endif
        </div>
    </div>

    {{-- ── REKOMENDASI ── --}}
    @if($rekomendasi->isNotEmpty())
    <div style="margin-top:56px">
        <div class="section-label">Produk Serupa</div>
        <h2 class="section-title" style="margin-bottom:24px">Mungkin <em>kamu suka</em></h2>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px">
            @foreach($rekomendasi as $p)
            @php
                // Normalisasi gambar rekomendasi secara aman
                $pGambarRaw = null;
                if ($p->relationLoaded('gambar')) {
                    $pRel = $p->getRelation('gambar');
                    if ($pRel instanceof \Illuminate\Support\Collection) {
                        $pGambarRaw = $pRel;
                    }
                }
                if ($pGambarRaw === null) {
                    $pGambarRaw = $p->attributes['gambar'] ?? null;
                }
                if ($pGambarRaw instanceof \Illuminate\Support\Collection) {
                    $pGambarUtama = $pGambarRaw->map(fn($g) => is_object($g) ? ($g->path ?? null) : $g)
                                               ->filter()->first();
                } elseif (is_string($pGambarRaw) && $pGambarRaw !== '') {
                    $pDecoded = json_decode($pGambarRaw, true);
                    $pArr = (json_last_error() === JSON_ERROR_NONE && is_array($pDecoded)) ? $pDecoded : [$pGambarRaw];
                    $pGambarUtama = array_values(array_filter($pArr))[0] ?? null;
                } elseif (is_array($pGambarRaw)) {
                    $pGambarUtama = array_values(array_filter(array_map(fn($g) => is_object($g) ? ($g->path ?? null) : $g, $pGambarRaw)))[0] ?? null;
                } else {
                    $pGambarUtama = null;
                }
            @endphp
            <div class="prod-card">
                <a href="{{ route('produk.show', $p->slug) }}" style="text-decoration:none;color:inherit">
                    <div class="prod-img">
                        @if($pGambarUtama)
                            <img src="{{ asset('storage/' . $pGambarUtama) }}" alt="{{ $p->nama }}" onerror="this.onerror=null; this.src='{{ asset('gambar/placeholder.svg') }}';">
                        @else
                            <img src="{{ asset('gambar/placeholder.svg') }}" alt="{{ $p->nama }}">
                        @endif
                        @if($p->harga_promo)
                            <div class="prod-img-badge"><span class="badge badge-sale">−{{ $p->diskon_persen }}%</span></div>
                        @endif
                    </div>
                    <div class="prod-body">
                        <div class="prod-cat">{{ $p->kategori->nama }}</div>
                        <div class="prod-name">{{ $p->nama }}</div>
                        <div class="prod-price-row">
                            <span class="prod-price">Rp {{ number_format($p->harga_final, 0, ',', '.') }}</span>
                            <span class="prod-unit">/{{ $p->satuan }}</span>
                        </div>
                        <div class="{{ $p->stok > 20 ? 'prod-stock-ok' : 'prod-stock-low' }}">
                            {{ $p->stok > 0 ? '✓ Stok: '.$p->stok.' '.$p->satuan : '✕ Habis' }}
                        </div>
                    </div>
                </a>
                <div class="prod-footer">
                    @if($p->stok > 0)
                    <form action="{{ route('keranjang.tambah') }}" method="POST" style="flex:1">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $p->id }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">+ Keranjang</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
    const hargaFinal = {{ $produk->harga_final }};
    const maxStok    = {{ $produk->stok }};

    function ubahQty(delta) {
        const inp = document.getElementById('qtyInput');
        let val = parseInt(inp.value) + delta;
        val = Math.max(1, Math.min(val, maxStok));
        inp.value = val;
        document.getElementById('totalHarga').textContent =
            'Rp ' + (hargaFinal * val).toLocaleString('id-ID');
    }

    function gantiGambar(src, el) {
        document.getElementById('mainImg').style.opacity = '0';
        setTimeout(() => {
            document.getElementById('mainImg').src = src;
            document.getElementById('mainImg').style.opacity = '1';
        }, 150);
        document.querySelectorAll('.thumb-item').forEach(t => t.style.borderColor = 'rgba(176,139,110,.2)');
        el.style.borderColor = 'var(--terracotta)';
    }

    function gantiTab(tab, btn) {
        document.querySelectorAll('[id^="tab-"]').forEach(t => t.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.style.color = 'var(--clay)';
            b.style.borderBottom = 'none';
            b.style.fontWeight = '600';
        });
        document.getElementById('tab-' + tab).style.display = tab === 'deskripsi' ? 'grid' : 'block';
        btn.style.color = 'var(--terracotta)';
        btn.style.borderBottom = '2px solid var(--terracotta)';
        btn.style.fontWeight = '700';
    }
</script>
@endpush