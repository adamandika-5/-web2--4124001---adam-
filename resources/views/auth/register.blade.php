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
        <div style="position:relative">
            <input id="reg-password" class="form-inp {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   type="password" name="password"
                   placeholder="Minimal 8 karakter" required style="padding-right:44px">
            <button type="button" id="toggle-reg-password"
                    aria-label="Tampilkan/Sembunyikan kata sandi"
                    onclick="togglePassword('reg-password','toggle-reg-password')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--clay);display:flex;align-items:center;justify-content:center;transition:color .2s;outline:none">
                {{-- Eye icon (password tersembunyi) --}}
                <svg id="toggle-reg-password-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{-- Eye-slash icon (password terlihat) --}}
                <svg id="toggle-reg-password-eye-slash" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-grp">
        <label class="form-lbl">Konfirmasi Kata Sandi <span style="color:var(--terracotta)">*</span></label>
        <div style="position:relative">
            <input id="reg-password-confirm" class="form-inp"
                   type="password" name="password_confirmation"
                   placeholder="Ulangi kata sandi" required style="padding-right:44px">
            <button type="button" id="toggle-reg-password-confirm"
                    aria-label="Tampilkan/Sembunyikan konfirmasi kata sandi"
                    onclick="togglePassword('reg-password-confirm','toggle-reg-password-confirm')"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--clay);display:flex;align-items:center;justify-content:center;transition:color .2s;outline:none">
                {{-- Eye icon --}}
                <svg id="toggle-reg-password-confirm-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{-- Eye-slash icon --}}
                <svg id="toggle-reg-password-confirm-eye-slash" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>
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
