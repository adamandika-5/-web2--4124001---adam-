@extends('layouts.auth')
@section('title', 'Daftar Akun')

@section('auth_visual')
    <h2 style="font-family:var(--fd);font-size:36px;font-weight:500;color:var(--sand);line-height:1.15;margin-bottom:14px">
        Mulai belanja<br><em style="font-style:italic;color:var(--terracotta)">hari ini.</em>
    </h2>
    <p style="font-size:14.5px;color:rgba(232,220,199,.5);max-width:310px;line-height:1.75">
        Buat akun gratis dan akses ribuan produk material bangunan dengan harga terbaik.
    </p>
@endsection

@section('content')
<h2 style="font-family:var(--fd);font-size:28px;font-weight:500;color:var(--soil);margin-bottom:6px">Buat Akun Baru</h2>
<p style="font-size:13.5px;color:var(--clay);margin-bottom:28px">
    Sudah punya akun?
    <a href="{{ route('login') }}" style="color:var(--terracotta);font-weight:700;text-decoration:none">Masuk di sini</a>
</p>

<form action="{{ route('register') }}" method="POST">
    @csrf

    <div class="form-grp">
        <label class="form-lbl">Nama Lengkap <span style="color:var(--terracotta)">*</span></label>
        <input class="form-inp {{ $errors->has('name') ? 'is-invalid' : '' }}"
               type="text" name="name" value="{{ old('name') }}"
               placeholder="Nama lengkap Anda" required autofocus>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-grp">
        <label class="form-lbl">Email <span style="color:var(--terracotta)">*</span></label>
        <input class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
               type="email" name="email" value="{{ old('email') }}"
               placeholder="email@contoh.com" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-grp">
        <label class="form-lbl">No. WhatsApp / Telepon</label>
        <input class="form-inp {{ $errors->has('telepon') ? 'is-invalid' : '' }}"
               type="text" name="telepon" value="{{ old('telepon') }}"
               placeholder="cth: 08123456789">
        @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-grp">
        <label class="form-lbl">Kata Sandi <span style="color:var(--terracotta)">*</span></label>
        <input class="form-inp {{ $errors->has('password') ? 'is-invalid' : '' }}"
               type="password" name="password"
               placeholder="Minimal 8 karakter" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-grp">
        <label class="form-lbl">Konfirmasi Kata Sandi <span style="color:var(--terracotta)">*</span></label>
        <input class="form-inp"
               type="password" name="password_confirmation"
               placeholder="Ulangi kata sandi" required>
    </div>

    <label style="display:flex;align-items:flex-start;gap:10px;margin-bottom:24px;cursor:pointer">
        <input type="checkbox" name="agree" value="1"
               style="width:15px;height:15px;margin-top:2px;flex-shrink:0;accent-color:var(--terracotta)"
               {{ old('agree') ? 'checked' : '' }}>
        <span style="font-size:13px;color:var(--clay);line-height:1.55">
            Saya menyetujui
            <a href="#" style="color:var(--terracotta);font-weight:600;text-decoration:none">syarat dan ketentuan</a>
            serta
            <a href="#" style="color:var(--terracotta);font-weight:600;text-decoration:none">kebijakan privasi</a>
            Sinar Alam.
        </span>
    </label>
    @error('agree') <div class="invalid-feedback" style="margin-top:-18px;margin-bottom:16px;display:block">{{ $message }}</div> @enderror

    <button type="submit" class="btn btn-primary"
            style="width:100%;justify-content:center;font-size:15px;padding:13px">
        Daftar Sekarang
    </button>
</form>
@endsection
