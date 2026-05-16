@extends('layouts.auth')
@section('title', 'Lupa Kata Sandi')

@section('auth_visual')
    <h2 style="font-family:var(--fd);font-size:36px;font-weight:500;color:var(--sand);line-height:1.15;margin-bottom:14px">
        Reset kata sandi<br><em style="font-style:italic;color:var(--terracotta)">Sinar Alam.</em>
    </h2>
    <p style="font-size:14.5px;color:rgba(232,220,199,.5);max-width:310px;line-height:1.75">
        Masukkan email yang terdaftar, kami kirimkan link untuk membuat kata sandi baru.
    </p>
@endsection

@section('content')
    <h2 style="font-family:var(--fd);font-size:26px;font-weight:500;color:var(--soil);margin-bottom:6px">Lupa Kata Sandi?</h2>
    <p style="font-size:13.5px;color:var(--clay);margin-bottom:28px">
        Ingat kata sandi? <a href="{{ route('login') }}" style="color:var(--terracotta);font-weight:700;text-decoration:none">Masuk di sini</a>
    </p>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:20px">
            ✓ {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-grp">
            <label class="form-lbl">Email Terdaftar</label>
            <input class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   type="email" name="email" value="{{ old('email') }}"
                   placeholder="contoh@email.com" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;padding:13px">
            Kirim Link Reset
        </button>
    </form>
@endsection