@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')
@section('breadcrumb', 'Inventaris › Produk › Edit')

@section('content')
<div style="max-width:850px; margin: 0 auto;">
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil)">
                ✏️ Edit Produk: {{ $produk->nama }}
            </div>
            <a href="{{ route('admin.produk.index') }}" style="font-size:13px;color:var(--clay);text-decoration:none;font-weight:600">
                ← Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
                
                {{-- Nama Produk --}}
                <div class="form-grp" style="grid-column: 1 / -1">
                    <label class="form-lbl">Nama Produk <span style="color:var(--terracotta)">*</span></label>
                    <input type="text" name="nama" class="form-inp @error('nama') is-invalid @enderror" 
                           value="{{ old('nama', $produk->nama) }}" placeholder="Contoh: Semen Gresik 50kg" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- SKU --}}
                <div class="form-grp">
                    <label class="form-lbl">SKU (Kode Barang)</label>
                    <input type="text" class="form-inp" value="{{ $produk->sku ?? '—' }}" disabled style="background:var(--oat);cursor:not-allowed">
                    <small style="color:var(--clay);font-size:11px;margin-top:4px;display:block">SKU tidak dapat diubah setelah produk dibuat.</small>
                </div>

                {{-- Kategori --}}
                <div class="form-grp">
                    <label class="form-lbl">Kategori <span style="color:var(--terracotta)">*</span></label>
                    <select name="kategori_id" class="form-inp @error('kategori_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $produk->kategori_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Satuan --}}
                <div class="form-grp">
                    <label class="form-lbl">Satuan <span style="color:var(--terracotta)">*</span></label>
                    <input type="text" name="satuan" class="form-inp @error('satuan') is-invalid @enderror" 
                           value="{{ old('satuan', $produk->satuan) }}" placeholder="Contoh: Sak, Pcs, Dus, Batang" required>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="form-grp">
                    <label class="form-lbl">Harga (Rp) <span style="color:var(--terracotta)">*</span></label>
                    <input type="number" name="harga" class="form-inp @error('harga') is-invalid @enderror" 
                           value="{{ old('harga', $produk->harga) }}" min="0" step="any" placeholder="Harga Jual Utama" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga Promo --}}
                <div class="form-grp">
                    <label class="form-lbl">Harga Promo (Rp) (Opsional)</label>
                    <input type="number" name="harga_promo" class="form-inp @error('harga_promo') is-invalid @enderror" 
                           value="{{ old('harga_promo', $produk->harga_promo) }}" min="0" step="any" placeholder="Kosongkan jika tidak promo">
                    @error('harga_promo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stok --}}
                <div class="form-grp">
                    <label class="form-lbl">Stok <span style="color:var(--terracotta)">*</span></label>
                    <input type="number" name="stok" class="form-inp @error('stok') is-invalid @enderror" 
                           value="{{ old('stok', $produk->stok) }}" min="0" placeholder="Stok saat ini" required>
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Berat --}}
                <div class="form-grp">
                    <label class="form-lbl">Berat (Gram) (Opsional)</label>
                    <input type="number" name="berat" class="form-inp @error('berat') is-invalid @enderror" 
                           value="{{ old('berat', $produk->berat) }}" min="0" placeholder="Contoh: 50000 untuk 50kg">
                    @error('berat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Pengiriman --}}
                <div class="form-grp" style="grid-column: 1 / -1">
                    <label class="form-lbl">Metode Pengiriman Layak <span style="color:var(--terracotta)">*</span></label>
                    <select name="jenis_pengiriman" class="form-inp @error('jenis_pengiriman') is-invalid @enderror" required>
                        <option value="ekspedisi" {{ old('jenis_pengiriman', $produk->jenis_pengiriman) === 'ekspedisi' ? 'selected' : '' }}>Ekspedisi / Cargo Jasa Pengiriman</option>
                        <option value="armada" {{ old('jenis_pengiriman', $produk->jenis_pengiriman) === 'armada' ? 'selected' : '' }}>Armada Toko Sinar Alam</option>
                        <option value="keduanya" {{ old('jenis_pengiriman', $produk->jenis_pengiriman) === 'keduanya' ? 'selected' : '' }}>Keduanya (Dapat dikirim ekspedisi & armada)</option>
                    </select>
                    @error('jenis_pengiriman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-grp" style="grid-column: 1 / -1">
                    <label class="form-lbl">Deskripsi Produk <span style="color:var(--terracotta)">*</span></label>
                    <textarea name="deskripsi" class="form-inp @error('deskripsi') is-invalid @enderror" 
                              rows="4" placeholder="Detail deskripsi mengenai produk..." required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Status Aktif & Unggulan --}}
                <div class="form-grp" style="grid-column: 1 / -1; display:flex; gap:32px; margin-top: 8px">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer">
                        <input type="checkbox" name="aktif" value="1" {{ old('aktif', $produk->aktif) ? 'checked' : '' }} 
                               style="width:18px; height:18px; accent-color:var(--terracotta)">
                        <div>
                            <span style="font-size:14px; font-weight:600; color:var(--soil)">Aktifkan Produk</span>
                            <div style="font-size:11.5px; color:var(--clay)">Tampilkan produk ini di halaman katalog umum pembeli.</div>
                        </div>
                    </label>

                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer">
                        <input type="checkbox" name="unggulan" value="1" {{ old('unggulan', $produk->unggulan) ? 'checked' : '' }} 
                               style="width:18px; height:18px; accent-color:var(--terracotta)">
                        <div>
                            <span style="font-size:14px; font-weight:600; color:var(--soil)">Jadikan Unggulan</span>
                            <div style="font-size:11.5px; color:var(--clay)">Tampilkan produk ini di bagian slide/rekomendasi depan.</div>
                        </div>
                    </label>
                </div>

                {{-- File Gambar --}}
                <div class="form-grp" style="grid-column: 1 / -1; margin-top: 12px">
                    <label class="form-lbl">Tambah Gambar Produk Baru</label>
                    <input type="file" name="gambar[]" class="form-inp @error('gambar.*') is-invalid @enderror" multiple accept="image/*">
                    <small style="color:var(--clay);font-size:11.5px;margin-top:4px;display:block">Format gambar: JPG, PNG, WEBP. Maksimal 2MB per gambar. Bisa memilih lebih dari 1 file.</small>
                    @error('gambar.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Existing Gambar dari relasi ProdukGambar --}}
                @php
                    // Ambil gambar dari relasi yang sudah di-eager-load oleh controller edit()
                    $gambarRelasi = collect();
                    if ($produk->relationLoaded('gambar')) {
                        $rel = $produk->getRelation('gambar');
                        if ($rel instanceof \Illuminate\Support\Collection) {
                            $gambarRelasi = $rel;
                        }
                    }
                    // Fallback: jika relasi kosong, coba query langsung
                    if ($gambarRelasi->isEmpty()) {
                        try {
                            $gambarRelasi = \App\Models\ProdukGambar::where('produk_id', $produk->id)
                                ->orderBy('urutan')->get();
                        } catch (\Exception $e) {
                            $gambarRelasi = collect();
                        }
                    }
                @endphp

                @if($gambarRelasi->isNotEmpty())
                    <div style="grid-column: 1 / -1; margin-top: 12px">
                        <label class="form-lbl" style="margin-bottom:12px">
                            Gambar Produk Saat Ini
                            <span style="font-size:11px;color:var(--clay);font-weight:400">({{ $gambarRelasi->count() }} foto — klik × untuk hapus)</span>
                        </label>
                        <div style="display:flex; flex-wrap:wrap; gap:16px" id="gambarGrid">
                            @foreach($gambarRelasi as $g)
                                <div id="gambar-item-{{ $g->id }}"
                                     style="position:relative; width:120px; height:120px; border-radius:var(--r-md); border:1.5px solid {{ $g->is_utama ? 'var(--terracotta)' : 'var(--sand)' }}; overflow:hidden; background:var(--oat); display:flex; align-items:center; justify-content:center; flex-shrink:0">
                                    <img src="{{ asset('storage/' . $g->path) }}"
                                         style="width:100%; height:100%; object-fit:cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                    <div style="display:none; align-items:center; justify-content:center; width:100%; height:100%; font-size:28px">📷</div>

                                    {{-- Badge Utama --}}
                                    @if($g->is_utama)
                                        <div style="position:absolute; top:4px; left:4px; background:var(--terracotta); color:#fff; font-size:9px; font-weight:700; padding:2px 6px; border-radius:99px; line-height:1.4; pointer-events:none">
                                            Utama
                                        </div>
                                    @endif

                                    {{-- Tombol Hapus --}}
                                    <button type="button"
                                            onclick="hapusGambarProduk({{ $g->id }}, 'gambar-item-{{ $g->id }}')"
                                            style="position:absolute; top:4px; right:4px; background:#dc2626; color:#fff; border:none; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:14px; font-weight:bold; line-height:1; box-shadow:0 2px 6px rgba(0,0,0,.25); padding:0"
                                            title="Hapus gambar ini">
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <small style="color:var(--clay);font-size:11px;margin-top:8px;display:block">
                            Gambar bertanda <strong style="color:var(--terracotta)">Utama</strong> akan menjadi foto utama produk. Hapus gambar utama akan otomatis memindahkan status ke gambar berikutnya.
                        </small>
                    </div>
                @else
                    <div style="grid-column: 1 / -1; margin-top: 4px">
                        <small style="color:var(--clay);font-size:12px">Belum ada gambar. Upload gambar baru di atas.</small>
                    </div>
                @endif

            </div>

            {{-- Tombol Aksi --}}
            <div style="display:flex;gap:14px;margin-top:32px;padding-top:20px;border-top:1px solid var(--sand)">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                    💾 Simpan Perubahan
                </button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center;text-decoration:none">
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function hapusGambarProduk(gambarId, elementId) {
        if (!confirm('Hapus gambar ini? Tindakan tidak dapat dibatalkan.')) return;

        const btn = document.querySelector(`#${elementId} button`);
        if (btn) { btn.disabled = true; btn.textContent = '…'; }

        fetch(`/admin/produk/gambar/${gambarId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.ok) {
                const el = document.getElementById(elementId);
                if (el) {
                    el.style.transition = 'opacity .25s, transform .25s';
                    el.style.opacity   = '0';
                    el.style.transform = 'scale(.85)';
                    setTimeout(() => el.remove(), 260);
                }
            } else {
                alert('Gagal menghapus gambar: ' + (data.message ?? 'unknown error'));
                if (btn) { btn.disabled = false; btn.textContent = '×'; }
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan koneksi saat menghapus gambar.');
            if (btn) { btn.disabled = false; btn.textContent = '×'; }
        });
    }
</script>
@endpush
