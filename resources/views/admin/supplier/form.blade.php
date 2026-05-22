@extends('layouts.admin')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('page_title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('breadcrumb', 'Layanan › Supplier › ' . (isset($supplier) ? 'Edit' : 'Tambah'))

@section('content')
<div style="max-width:680px">
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            {{ isset($supplier) ? 'Edit Supplier: '.$supplier->nama : 'Tambah Supplier Baru' }}
        </div>

        <form action="{{ isset($supplier) ? route('admin.supplier.update', $supplier->id) : route('admin.supplier.store') }}"
              method="POST">
            @csrf
            @if(isset($supplier)) @method('PUT') @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Nama Supplier / Perusahaan *</label>
                    <input class="form-inp {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                           type="text" name="nama"
                           value="{{ old('nama', $supplier->nama ?? '') }}"
                           placeholder="PT. Semen Indonesia" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Nama Kontak / PIC</label>
                    <input class="form-inp" type="text" name="kontak"
                           value="{{ old('kontak', $supplier->kontak ?? '') }}"
                           placeholder="Budi Santoso">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">No. Telepon</label>
                    <input class="form-inp" type="tel" name="telepon"
                           value="{{ old('telepon', $supplier->telepon ?? '') }}"
                           placeholder="081234567890">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Email</label>
                    <input class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           type="email" name="email"
                           value="{{ old('email', $supplier->email ?? '') }}"
                           placeholder="supplier@email.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Kota</label>
                    <input class="form-inp" type="text" name="kota"
                           value="{{ old('kota', $supplier->kota ?? '') }}"
                           placeholder="Gresik">
                </div>

                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Alamat Lengkap</label>
                    <textarea class="form-inp" name="alamat" rows="2"
                              placeholder="Jl. Industri No. xx, Kawasan Industri...">{{ old('alamat', $supplier->alamat ?? '') }}</textarea>
                </div>

                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Catatan Internal</label>
                    <textarea class="form-inp" name="catatan" rows="2"
                              placeholder="Catatan khusus, syarat pembayaran, dll...">{{ old('catatan', $supplier->catatan ?? '') }}</textarea>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                    💾 {{ isset($supplier) ? 'Simpan Perubahan' : 'Tambah Supplier' }}
                </button>
                <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection