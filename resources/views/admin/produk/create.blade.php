@extends('layouts.admin')
@section('title', 'Tambah Produk Baru')
@section('page_title', 'Tambah Produk Baru')
@section('breadcrumb', 'Inventaris › Produk › Tambah')

@section('content')
<div style="max-width:850px; margin: 0 auto;">
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil)">
                📦 Tambah Produk Baru
            </div>
            <a href="{{ route('admin.produk.index') }}" style="font-size:13px;color:var(--clay);text-decoration:none;font-weight:600">
                ← Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
                
                {{-- Nama Produk --}}
                <div class="form-grp" style="grid-column: 1 / -1">
                    <label class="form-lbl">Nama Produk <span style="color:var(--terracotta)">*</span></label>
                    <input type="text" name="nama" class="form-inp @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" placeholder="Contoh: Semen Gresik 50kg" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- SKU --}}
                <div class="form-grp">
                    <label class="form-lbl">SKU (Kode Barang) (Opsional)</label>
                    <input type="text" name="sku" class="form-inp @error('sku') is-invalid @enderror" 
                           value="{{ old('sku') }}" placeholder="Contoh: SMN-GRS-50KG">
                    <small style="color:var(--clay);font-size:11px;margin-top:4px;display:block">Kosongkan jika ingin dibuat otomatis secara unik.</small>
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="form-grp">
                    <label class="form-lbl">Kategori <span style="color:var(--terracotta)">*</span></label>
                    <select name="kategori_id" class="form-inp @error('kategori_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Sub Kategori --}}
                <div class="form-grp">
                    <label class="form-lbl">Sub Kategori</label>
                    <select name="sub_kategori_id" class="form-inp @error('sub_kategori_id') is-invalid @enderror">
                        <option value="">-- Pilih Sub Kategori (Opsional) --</option>
                        @foreach($subKategoris as $sub)
                            <option value="{{ $sub->id }}" {{ old('sub_kategori_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->nama }} ({{ $sub->kategori->nama ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('sub_kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Satuan --}}
                <div class="form-grp">
                    <label class="form-lbl">Satuan <span style="color:var(--terracotta)">*</span></label>
                    <input type="text" name="satuan" class="form-inp @error('satuan') is-invalid @enderror" 
                           value="{{ old('satuan') }}" placeholder="Contoh: Sak, Pcs, Dus, Batang" required>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="form-grp">
                    <label class="form-lbl">Harga (Rp) <span style="color:var(--terracotta)">*</span></label>
                    <input type="number" name="harga" class="form-inp @error('harga') is-invalid @enderror" 
                           value="{{ old('harga') }}" min="0" step="any" placeholder="Harga Jual Utama" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga Promo --}}
                <div class="form-grp">
                    <label class="form-lbl">Harga Promo (Rp) (Opsional)</label>
                    <input type="number" name="harga_promo" class="form-inp @error('harga_promo') is-invalid @enderror" 
                           value="{{ old('harga_promo') }}" min="0" step="any" placeholder="Kosongkan jika tidak promo">
                    @error('harga_promo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stok --}}
                <div class="form-grp">
                    <label class="form-lbl">Stok <span style="color:var(--terracotta)">*</span></label>
                    <input type="number" name="stok" class="form-inp @error('stok') is-invalid @enderror" 
                           value="{{ old('stok', 0) }}" min="0" placeholder="Stok awal produk" required>
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Berat --}}
                <div class="form-grp">
                    <label class="form-lbl">Berat (Gram) (Opsional)</label>
                    <input type="number" name="berat" class="form-inp @error('berat') is-invalid @enderror" 
                           value="{{ old('berat') }}" min="0" placeholder="Contoh: 50000 untuk 50kg">
                    @error('berat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Pengiriman --}}
                <div class="form-grp" style="grid-column: 1 / -1">
                    <label class="form-lbl">Metode Pengiriman Layak <span style="color:var(--terracotta)">*</span></label>
                    <select name="jenis_pengiriman" class="form-inp @error('jenis_pengiriman') is-invalid @enderror" required>
                        <option value="ekspedisi" {{ old('jenis_pengiriman') === 'ekspedisi' ? 'selected' : '' }}>Ekspedisi / Cargo Jasa Pengiriman</option>
                        <option value="armada" {{ old('jenis_pengiriman') === 'armada' ? 'selected' : '' }}>Armada Toko Sinar Alam</option>
                        <option value="keduanya" {{ old('jenis_pengiriman', 'keduanya') === 'keduanya' ? 'selected' : '' }}>Keduanya (Dapat dikirim ekspedisi & armada)</option>
                    </select>
                    @error('jenis_pengiriman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-grp" style="grid-column: 1 / -1">
                    <label class="form-lbl">Deskripsi Produk <span style="color:var(--terracotta)">*</span></label>
                    <textarea name="deskripsi" class="form-inp @error('deskripsi') is-invalid @enderror" 
                              rows="4" placeholder="Detail deskripsi mengenai produk..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Spesifikasi --}}
                <div class="form-grp" style="grid-column: 1 / -1">
                    <label class="form-lbl">Spesifikasi Produk (Opsional)</label>
                    <textarea name="spesifikasi" class="form-inp @error('spesifikasi') is-invalid @enderror" 
                              rows="3" placeholder="Contoh: Dimensi, Material, Merek, Daya Tahan, dll...">{{ old('spesifikasi') }}</textarea>
                    @error('spesifikasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status Aktif & Unggulan --}}
                <div class="form-grp" style="grid-column: 1 / -1; display:flex; gap:32px; margin-top: 8px">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer">
                        <input type="checkbox" name="aktif" value="1" {{ old('aktif', 1) ? 'checked' : '' }} 
                               style="width:18px; height:18px; accent-color:var(--terracotta)">
                        <div>
                            <span style="font-size:14px; font-weight:600; color:var(--soil)">Aktifkan Produk</span>
                            <div style="font-size:11.5px; color:var(--clay)">Tampilkan produk ini di halaman katalog umum pembeli.</div>
                        </div>
                    </label>

                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer">
                        <input type="checkbox" name="unggulan" value="1" {{ old('unggulan') ? 'checked' : '' }} 
                               style="width:18px; height:18px; accent-color:var(--terracotta)">
                        <div>
                            <span style="font-size:14px; font-weight:600; color:var(--soil)">Jadikan Unggulan</span>
                            <div style="font-size:11.5px; color:var(--clay)">Tampilkan produk ini di bagian slide/rekomendasi depan.</div>
                        </div>
                    </label>
                </div>

                {{-- File Gambar --}}
                <div class="form-grp" style="grid-column: 1 / -1; margin-top: 12px">
                    <label class="form-lbl">Gambar Produk</label>
                    <input type="file" name="gambar[]" class="form-inp @error('gambar.*') is-invalid @enderror" multiple accept="image/*">
                    <small style="color:var(--clay);font-size:11.5px;margin-top:4px;display:block">Format gambar: JPG, PNG, WEBP. Maksimal 2MB per gambar. Bisa memilih lebih dari 1 file.</small>
                    @error('gambar.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div style="display:flex;gap:14px;margin-top:32px;padding-top:20px;border-top:1px solid var(--sand)">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                    ➕ Tambah Produk
                </button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center;text-decoration:none">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
