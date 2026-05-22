@extends('layouts.admin')
@section('title', 'Edit Promo')
@section('page_title', 'Edit Promo')
@section('breadcrumb', 'Pemasaran › Promo › Edit')

@section('content')
<div style="max-width:640px">
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            Edit Promo: {{ $promo->nama }}
        </div>

        <form action="{{ route('admin.promo.update', $promo->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-grp">
                <label class="form-lbl">Nama Promo *</label>
                <input class="form-inp {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                       type="text" name="nama" value="{{ old('nama', $promo->nama) }}" required>
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-grp">
                <label class="form-lbl">Label Banner</label>
                <input class="form-inp" type="text" name="label"
                       value="{{ old('label', $promo->label) }}"
                       placeholder="Contoh: Flash Sale, Promo Akhir Pekan">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-grp">
                    <label class="form-lbl">Tipe Diskon *</label>
                    <select class="form-inp" name="tipe" required>
                        @foreach(['persentase'=>'Persentase (%)','nominal'=>'Nominal (Rp)','gratis_ongkir'=>'Gratis Ongkir'] as $v => $l)
                            <option value="{{ $v }}" {{ old('tipe', $promo->tipe) === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Nilai *</label>
                    <input class="form-inp" type="number" name="nilai"
                           value="{{ old('nilai', $promo->nilai) }}" min="0" required>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Min. Belanja (Rp)</label>
                    <input class="form-inp" type="number" name="min_belanja"
                           value="{{ old('min_belanja', $promo->min_belanja) }}" min="0">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Maks. Diskon (Rp)</label>
                    <input class="form-inp" type="number" name="maks_diskon"
                           value="{{ old('maks_diskon', $promo->maks_diskon) }}" min="0"
                           placeholder="Kosongkan = unlimited">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Mulai *</label>
                    <input class="form-inp" type="datetime-local" name="mulai_at"
                           value="{{ old('mulai_at', $promo->mulai_at?->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Berakhir *</label>
                    <input class="form-inp" type="datetime-local" name="berakhir_at"
                           value="{{ old('berakhir_at', $promo->berakhir_at?->format('Y-m-d\TH:i')) }}" required>
                </div>
            </div>

            <label style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13.5px;color:var(--soil);cursor:pointer">
                <input type="hidden" name="aktif" value="0">
                <input type="checkbox" name="aktif" value="1"
                       {{ old('aktif', $promo->aktif) ? 'checked' : '' }}
                       style="accent-color:var(--terracotta);width:17px;height:17px">
                Promo aktif
            </label>

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">💾 Simpan</button>
                <a href="{{ route('admin.promo.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection