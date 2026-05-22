@extends('layouts.admin')
@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('page_title', isset($user) ? 'Edit User' : 'Tambah User Baru')
@section('breadcrumb', 'Sistem › User › ' . (isset($user) ? 'Edit' : 'Tambah'))

@section('content')

<div style="max-width:640px">
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">

        <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            {{ isset($user) ? 'Edit Data User' : 'Tambah User Baru' }}
        </div>

        <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
              method="POST">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Nama Lengkap *</label>
                    <input class="form-inp {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           type="text" name="name"
                           value="{{ old('name', $user->name ?? '') }}"
                           placeholder="Nama lengkap" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Email *</label>
                    <input class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           type="email" name="email"
                           value="{{ old('email', $user->email ?? '') }}"
                           placeholder="email@domain.com" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">No. Telepon</label>
                    <input class="form-inp" type="tel" name="telepon"
                           value="{{ old('telepon', $user->telepon ?? '') }}"
                           placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Role *</label>
                    <select class="form-inp" name="role" required>
                        <option value="user"  {{ old('role', $user->role ?? 'user') === 'user'  ? 'selected' : '' }}>Pelanggan (User)</option>
                        <option value="staff" {{ old('role', $user->role ?? '') === 'staff' ? 'selected' : '' }}>Staff Admin</option>
                        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if(!isset($user))
                {{-- Password hanya di form tambah --}}
                <div class="form-grp">
                    <label class="form-lbl">Kata Sandi *</label>
                    <input class="form-inp {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           type="password" name="password"
                           placeholder="Min. 8 karakter" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Konfirmasi Sandi *</label>
                    <input class="form-inp" type="password"
                           name="password_confirmation"
                           placeholder="Ulangi kata sandi" required>
                </div>
                @endif
            </div>

            @if(isset($user) && $user->id !== auth()->id())
            <label style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13.5px;color:var(--soil);cursor:pointer">
                <input type="hidden" name="aktif" value="0">
                <input type="checkbox" name="aktif" value="1"
                       {{ old('aktif', $user->aktif ?? true) ? 'checked' : '' }}
                       style="accent-color:var(--terracotta);width:17px;height:17px">
                Akun aktif
            </label>
            @endif

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                    💾 {{ isset($user) ? 'Simpan Perubahan' : 'Tambah User' }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection