@extends('layouts.admin')
@section('title', isset($alat) ? 'Edit Alat' : 'Tambah Alat')
@section('page_title', isset($alat) ? 'Edit Alat: '.$alat->nama : 'Tambah Alat Baru')
@section('breadcrumb', 'Layanan › Sewa Alat › ' . (isset($alat) ? 'Edit' : 'Tambah'))

@section('content')
<div style="max-width:760px">
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            {{ isset($alat) ? 'Edit Alat Bangunan' : 'Tambah Alat Bangunan Baru' }}
        </div>

        <form action="{{ isset($alat) ? route('admin.sewa.update', $alat->id) : route('admin.sewa.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($alat)) @method('PUT') @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">

                {{-- Nama & Kategori --}}
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Nama Alat *</label>
                    <input class="form-inp {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                           type="text" name="nama"
                           value="{{ old('nama', $alat->nama ?? '') }}"
                           placeholder="Scaffolding Set 50 Frame" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Kategori Alat</label>
                    <input class="form-inp" type="text" name="kategori_alat"
                           value="{{ old('kategori_alat', $alat->kategori_alat ?? '') }}"
                           placeholder="Scaffolding, Mixer, Alat Potong...">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Kondisi</label>
                    <select class="form-inp" name="kondisi">
                        @foreach(['baik'=>'Baik','cukup'=>'Cukup','perbaikan'=>'Dalam Perbaikan'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('kondisi', $alat->kondisi ?? 'baik') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tarif --}}
                <div class="form-grp">
                    <label class="form-lbl">Tarif per Hari (Rp) *</label>
                    <input class="form-inp {{ $errors->has('tarif_harian') ? 'is-invalid' : '' }}"
                           type="number" name="tarif_harian"
                           value="{{ old('tarif_harian', $alat->tarif_harian ?? '') }}"
                           placeholder="75000" min="0" required>
                    @error('tarif_harian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Tarif per Minggu (Rp)</label>
                    <input class="form-inp" type="number" name="tarif_mingguan"
                           value="{{ old('tarif_mingguan', $alat->tarif_mingguan ?? '') }}"
                           placeholder="420000" min="0">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Tarif per Bulan (Rp)</label>
                    <input class="form-inp" type="number" name="tarif_bulanan"
                           value="{{ old('tarif_bulanan', $alat->tarif_bulanan ?? '') }}"
                           placeholder="1500000" min="0">
                </div>

                {{-- Deposit & Denda --}}
                <div class="form-grp">
                    <label class="form-lbl">Deposit (Rp)</label>
                    <input class="form-inp" type="number" name="deposit"
                           value="{{ old('deposit', $alat->deposit ?? 0) }}"
                           placeholder="1000000" min="0">
                    <div style="font-size:11.5px;color:var(--clay);margin-top:4px">Dikembalikan saat alat kembali</div>
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Denda per Hari Terlambat (Rp)</label>
                    <input class="form-inp" type="number" name="denda_per_hari"
                           value="{{ old('denda_per_hari', $alat->denda_per_hari ?? 0) }}"
                           placeholder="100000" min="0">
                </div>

                {{-- Unit --}}
                <div class="form-grp">
                    <label class="form-lbl">Jumlah Unit *</label>
                    <input class="form-inp {{ $errors->has('jumlah_unit') ? 'is-invalid' : '' }}"
                           type="number" name="jumlah_unit"
                           value="{{ old('jumlah_unit', $alat->jumlah_unit ?? 1) }}"
                           placeholder="1" min="1" required>
                    @error('jumlah_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if(isset($alat))
                <div class="form-grp">
                    <label class="form-lbl">Unit Tersedia</label>
                    <input class="form-inp" type="number" name="tersedia"
                           value="{{ old('tersedia', $alat->tersedia ?? 1) }}"
                           min="0" max="{{ $alat->jumlah_unit ?? 99 }}">
                    <div style="font-size:11.5px;color:var(--clay);margin-top:4px">
                        Maksimal: {{ $alat->jumlah_unit }} unit
                    </div>
                </div>
                @endif

                {{-- Deskripsi --}}
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Deskripsi Alat</label>
                    <textarea class="form-inp" name="deskripsi" rows="3"
                              placeholder="Jelaskan spesifikasi, kapasitas, dan kegunaan alat...">{{ old('deskripsi', $alat->deskripsi ?? '') }}</textarea>
                </div>

                {{-- Catatan --}}
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Catatan Internal</label>
                    <textarea class="form-inp" name="catatan" rows="2"
                              placeholder="Catatan khusus untuk admin (tidak tampil ke pelanggan)...">{{ old('catatan', $alat->catatan ?? '') }}</textarea>
                </div>

                {{-- Foto --}}
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Foto Alat</label>
                    @if(isset($alat) && $alat->gambar)
                        <div style="margin-bottom:10px">
                            <img src="{{ asset('storage/'.$alat->gambar) }}"
                                 style="height:80px;border-radius:var(--r-md);border:1px solid var(--sand)">
                        </div>
                    @endif
                    <input type="file" name="gambar" accept="image/*"
                           class="form-inp" style="padding:7px;font-size:13px">
                    <div style="font-size:11.5px;color:var(--clay);margin-top:4px">JPG/PNG, maks 3MB</div>
                </div>
            </div>

            <label style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13.5px;color:var(--soil);cursor:pointer">
                <input type="hidden" name="aktif" value="0">
                <input type="checkbox" name="aktif" value="1"
                       {{ old('aktif', $alat->aktif ?? true) ? 'checked' : '' }}
                       style="accent-color:var(--terracotta);width:17px;height:17px">
                Alat aktif (tampil di katalog sewa)
            </label>

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:13px">
                    💾 {{ isset($alat) ? 'Simpan Perubahan' : 'Tambah Alat' }}
                </button>
                <a href="{{ route('admin.sewa.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection