@extends('layouts.auth')
@section('title', 'Masuk ke Akun')

@section('auth_visual')
    <h2 style="font-family:var(--fd);font-size:38px;font-weight:500;color:var(--sand);line-height:1.15;margin-bottom:14px">
        Masuk ke<br><em style="font-style:italic;color:var(--terracotta)">Sinar Alam.</em>
    </h2>
    <p style="font-size:14.5px;color:rgba(232,220,199,.5);max-width:310px;line-height:1.75">
        Akses keranjang, riwayat pesanan, dan sewa alat bangunan Anda.
    </p>
@endsection

@section('content')
    <h2 style="font-family:var(--fd);font-size:28px;font-weight:500;color:var(--soil);margin-bottom:6px">Masuk</h2>
    <p style="font-size:13.5px;color:var(--clay);margin-bottom:28px">
        Belum punya akun?
        <a href="{{ route('register') }}" style="color:var(--terracotta);font-weight:700;text-decoration:none">Daftar gratis</a>
    </p>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-grp">
            <label class="form-lbl">Email</label>
            <input class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   type="email" name="email" value="{{ old('email') }}"
                   placeholder="email@contoh.com" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-grp">
            <label class="form-lbl" style="display:flex;justify-content:space-between">
                Kata Sandi
                <a href="{{ route('password.request') }}"
                   style="font-size:12px;color:var(--terracotta);font-weight:600;text-decoration:none">
                    Lupa kata sandi?
                </a>
            </label>
            <input class="form-inp {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   type="password" name="password"
                   placeholder="••••••••" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <label style="display:flex;align-items:center;gap:8px;margin-bottom:24px;cursor:pointer">
            <input type="checkbox" name="remember" style="width:15px;height:15px;accent-color:var(--terracotta)">
            <span style="font-size:13px;color:var(--clay)">Ingat saya</span>
        </label>

        <button type="submit" class="btn btn-primary"
                style="width:100%;justify-content:center;font-size:15px;padding:13px">
            Masuk
        </button>
    </form>

    {{-- Google Login (hanya tampil jika GOOGLE_CLIENT_ID sudah dikonfigurasi) --}}
    @if(config('services.google.client_id'))
    <div style="margin:20px 0;display:flex;align-items:center;gap:12px">
        <div style="flex:1;height:1px;background:var(--sand)"></div>
        <span style="font-size:12px;color:var(--clay)">atau</span>
        <div style="flex:1;height:1px;background:var(--sand)"></div>
    </div>

    <a href="{{ route('login.google') }}"
       style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:12px;border:1.5px solid var(--sand);border-radius:var(--r-md);font-family:var(--fb);font-size:14px;font-weight:600;color:var(--soil);text-decoration:none;transition:all .2s;background:#fff"
       onmouseover="this.style.background='var(--oat)'"
       onmouseout="this.style.background='#fff'">
        <svg width="18" height="18" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Masuk dengan Google
    </a>
    @endif
@endsection
