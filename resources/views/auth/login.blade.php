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
            <div style="position:relative">
                <input id="login-password" class="form-inp {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       type="password" name="password"
                       placeholder="••••••••" required style="padding-right:44px">
                <button type="button" id="toggle-login-password"
                        aria-label="Tampilkan/Sembunyikan kata sandi"
                        onclick="togglePassword('login-password','toggle-login-password')"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--clay);display:flex;align-items:center;justify-content:center;transition:color .2s;outline:none">
                    {{-- Eye icon (password tersembunyi) --}}
                    <svg id="toggle-login-password-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{-- Eye-slash icon (password terlihat) --}}
                    <svg id="toggle-login-password-eye-slash" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
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

@push('scripts')
<script>
/**
 * Toggle show/hide password
 * @param {string} inputId  - ID dari elemen <input>
 * @param {string} btnId    - ID dari elemen <button> toggle
 */
function togglePassword(inputId, btnId) {
    const input   = document.getElementById(inputId);
    const eyeOn   = document.getElementById(btnId + '-eye');
    const eyeOff  = document.getElementById(btnId + '-eye-slash');
    const btn     = document.getElementById(btnId);

    if (!input) return;

    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';

    // Tukar icon
    if (eyeOn)  eyeOn.style.display  = isHidden ? 'none'  : '';
    if (eyeOff) eyeOff.style.display = isHidden ? ''      : 'none';

    // Warna icon berubah saat password terlihat
    if (btn) btn.style.color = isHidden ? 'var(--terracotta)' : 'var(--clay)';
}
</script>
@endpush
