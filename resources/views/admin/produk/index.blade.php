@extends('layouts.admin')
@section('title', 'Manajemen Produk')
@section('page_title', 'Manajemen Produk')
@section('breadcrumb', 'Inventaris › Produk')

@section('content')

{{-- ── TOOLBAR & FILTER ── --}}
<div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;margin-bottom:24px">
    
    {{-- Form Filter --}}
    <form method="GET" action="{{ route('admin.produk.index') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        <input class="form-inp" type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari nama atau SKU..."
               style="width:220px;padding:8px 12px;font-size:13px">
               
        <select class="form-inp" name="kategori" style="width:150px;font-size:13px;padding:8px">
            <option value="">Semua Kategori</option>
            @foreach($kategoris ?? [] as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama }}
                </option>
            @endforeach
        </select>
        
        <select class="form-inp" name="status" style="width:130px;font-size:13px;padding:8px">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="promo" {{ request('status') === 'promo' ? 'selected' : '' }}>Promo</option>
            <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
        </select>

        <select class="form-inp" name="sort" style="width:140px;font-size:13px;padding:8px">
            <option value="">Urutkan: Terbaru</option>
            <option value="stok_asc" {{ request('sort') === 'stok_asc' ? 'selected' : '' }}>Stok Terendah</option>
            <option value="terlaris" {{ request('sort') === 'terlaris' ? 'selected' : '' }}>Terlaris</option>
            <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
        </select>

        <button type="submit" class="btn btn-secondary" style="font-size:13px;padding:8px 14px">Filter</button>
        
        @if(request()->hasAny(['q', 'kategori', 'status', 'sort']))
            <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary" style="font-size:13px;padding:8px 14px;text-decoration:none;display:inline-flex;align-items:center">Reset</a>
        @endif
    </form>

    {{-- Tombol Aksi Kanan --}}
    <div style="display:flex;gap:8px">
        <a href="{{ route('admin.produk.export') }}" class="btn btn-secondary" style="font-size:13px;padding:8px 16px;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
            📊 Export Excel
        </a>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary" style="font-size:13px;padding:8px 18px;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
            + Tambah Produk
        </a>
    </div>
</div>

{{-- ── TABEL PRODUK ── --}}
<div class="adm-tbl-wrap" style="margin-bottom:20px">
    <table class="data-tbl">
        <thead>
            <tr>
                <th style="width:110px">SKU</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th style="text-align:right;width:140px">Harga</th>
                <th style="text-align:right;width:100px">Stok</th>
                <th style="text-align:center;width:90px">Status</th>
                <th style="text-align:center;width:120px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk ?? [] as $p)
            <tr class="row-hover">
                
                {{-- SKU --}}
                <td style="padding:12px 16px;font-family:monospace;color:var(--clay)">
                    {{ $p->sku ?? '—' }}
                </td>
                
                {{-- Produk --}}
                <td style="padding:12px 16px">
                    <div style="display:flex;align-items:center;gap:12px">
                        {{-- Thumbnail --}}
                        <div style="width:40px;height:40px;background:var(--oat);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:20px;overflow:hidden;flex-shrink:0;border:1px solid rgba(176,139,110,.08)">
                            @php
                                $gambarPath = null;
                                if ($p->relationLoaded('gambar') && $p->getRelation('gambar')) {
                                    $firstImg = $p->getRelation('gambar')->first();
                                    $gambarPath = $firstImg?->path;
                                } elseif (isset($p->gambar_utama)) {
                                    $gambarPath = $p->gambar_utama;
                                }
                            @endphp
                            
                            @if($gambarPath)
                                <img src="{{ asset('storage/' . $gambarPath) }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                                {{ $p->ikon ?? '📦' }}
                            @endif
                        </div>
                        
                        <div>
                            <div style="font-weight:600;color:var(--soil)">{{ $p->nama }}</div>
                            @if($p->unggulan)
                                <span style="background:rgba(198,107,61,.1);color:var(--terracotta);font-size:10px;padding:1px 6px;border-radius:99px;font-weight:700">Unggulan</span>
                            @endif
                        </div>
                    </div>
                </td>
                
                {{-- Kategori --}}
                <td style="padding:12px 16px;color:var(--soil-light)">
                    {{ $p->kategori->nama ?? '—' }}
                </td>
                
                {{-- Harga --}}
                <td style="padding:12px 16px;text-align:right">
                    @if($p->harga_promo)
                        <div style="font-weight:700;color:var(--terracotta)">Rp {{ number_format($p->harga_promo, 0, ',', '.') }}</div>
                        <div style="font-size:11px;color:var(--clay);text-decoration:line-through">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    @else
                        <div style="font-weight:600;color:var(--soil)">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    @endif
                </td>
                
                {{-- Stok --}}
                <td style="padding:12px 16px;text-align:right">
                    @if($p->stok == 0)
                        <div style="color:#dc2626;font-weight:700">Habis</div>
                    @elseif($p->stok < 20)
                        <div style="color:#d97706;font-weight:700">{{ number_format($p->stok) }} <span style="font-size:11.5px;font-weight:400;color:var(--clay)">{{ $p->satuan }}</span></div>
                        <div style="font-size:10px;color:#d97706">⚠️ Stok Rendah</div>
                    @else
                        <div style="color:var(--soil);font-weight:600">{{ number_format($p->stok) }} <span style="font-size:11.5px;font-weight:400;color:var(--clay)">{{ $p->satuan }}</span></div>
                    @endif
                </td>
                
                {{-- Status --}}
                <td style="padding:12px 16px;text-align:center">
                    <form action="{{ route('admin.produk.toggleAktif', $p) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                style="border:none;background:transparent;cursor:pointer;padding:0;outline:none"
                                title="Klik untuk {{ $p->aktif ? 'menonaktifkan' : 'mengaktifkan' }}">
                            <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;
                                background:{{ $p->aktif ? 'rgba(22,163,74,.1)' : 'rgba(220,38,38,.1)' }};
                                color:{{ $p->aktif ? '#16a34a' : '#dc2626' }}">
                                {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </button>
                    </form>
                </td>
                
                {{-- Aksi --}}
                <td style="padding:12px 16px;text-align:center">
                    <div style="display:flex;gap:12px;justify-content:center;align-items:center">
                        <a href="{{ route('admin.produk.edit', $p) }}"
                           style="font-size:12.5px;color:var(--terracotta);font-weight:700;text-decoration:none">Edit</a>
                        
                        <form action="{{ route('admin.produk.destroy', $p) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                              style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="border:none;background:transparent;padding:0;font-size:12.5px;color:#dc2626;font-weight:700;cursor:pointer;font-family:var(--fb)">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
                
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:32px;text-align:center;color:var(--clay)">
                    Data produk tidak ditemukan atau kosong.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── PAGINATION ── --}}
<div style="display:flex;justify-content:center;margin-top:20px">
    @if(method_exists($produk ?? null, 'links'))
        {{ $produk->links() }}
    @endif
</div>

@endsection
