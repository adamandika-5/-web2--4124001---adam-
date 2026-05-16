@extends('layouts.auth')
@section('title', 'Reset Kata Sandi')

@section('auth_visual')
    <h2 style="font-family:var(--fd);font-size:36px;font-weight:500;color:var(--sand);line-height:1.15;margin-bottom:14px">
        Buat kata sandi<br><em style="font-style:italic;color:var(--terracotta)">baru.</em>
    </h2>
    <p style="font-size:14.5px;color:rgba(232,220,199,.5);line-height:1.75">
        Gunakan kombinasi huruf, angka, dan simbol untuk kata sandi yang kuat.
    </p>
@endsection

@section('content')
    <h2 style="font-family:var(--fd);font-size:26px;font-weight:500;color:var(--soil);margin-bottom:6px">Reset Kata Sandi</h2>
    <p style="font-size:13.5px;color:var(--clay);margin-bottom:28px">Masukkan kata sandi baru untuk akun Anda.</p>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-grp">
            <label class="form-lbl">Email</label>
            <input class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   type="email" name="email" value="{{ old('email', $email ?? '') }}" required readonly
                   style="background:var(--oat);cursor:not-allowed">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-grp">
            <label class="form-lbl">Kata Sandi Baru</label>
            <input class="form-inp {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   type="password" name="password" placeholder="Min. 8 karakter" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-grp">
            <label class="form-lbl">Konfirmasi Kata Sandi</label>
            <input class="form-inp" type="password" name="password_confirmation"
                   placeholder="Ulangi kata sandi baru" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;padding:13px">
            Simpan Kata Sandi Baru
        </button>
    </form>
@endsection