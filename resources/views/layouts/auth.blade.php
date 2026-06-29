<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — Sinar Alam</title>
    <meta name="description" content="Masuk ke akun Sinar Alam untuk berbelanja material bangunan.">
    
    @php
        $faviconPath = \App\Models\Pengaturan::get('favicon');
        $faviconVersion = \App\Models\Pengaturan::where('kunci', 'favicon')->first()?->updated_at?->timestamp ?? 'default';
        $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,700;1,9..144,500&family=Epilogue:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bangunmart.css') }}">

    @stack('styles')
</head>
<body>

{{-- Layout 2 kolom desktop / 1 kolom mobile --}}
<div class="auth-layout">

    {{-- ── PANEL KIRI (visual / branding) ── --}}
    <div class="auth-panel-left">

        {{-- Background pattern --}}
        <div style="position:absolute;inset:0;opacity:.04;background-image:radial-gradient(circle,var(--sand) 1px,transparent 1px);background-size:24px 24px;pointer-events:none"></div>

        {{-- Logo --}}
        <a href="{{ route('beranda') }}" style="display:inline-block;margin-bottom:48px">
            <img src="{{ asset('gambar/logo-sinar-alam.png') }}"
                 alt="Sinar Alam"
                 style="height:48px;width:auto"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
            <span style="display:none;font-family:var(--fd);font-size:28px;font-weight:700;color:var(--sand)">Sinar Alam</span>
        </a>

        {{-- Visual section dari masing-masing halaman --}}
        @hasSection('auth_visual')
            @yield('auth_visual')
        @else
            <h2 style="font-family:var(--fd);font-size:40px;font-weight:500;color:var(--sand);line-height:1.15;margin-bottom:16px">
                Selamat datang<br><em style="font-style:italic;color:var(--terracotta)">kembali.</em>
            </h2>
            <p style="font-size:15px;color:rgba(232,220,199,.5);max-width:320px;line-height:1.75">
                Masuk ke akun Sinar Alam untuk melanjutkan belanja material bangunan Anda.
            </p>
        @endif

        {{-- Decorative features list --}}
        <div style="margin-top:48px;display:flex;flex-direction:column;gap:14px">
            @foreach(['🏪 Toko material bangunan terpercaya', '🚚 Pengiriman ke seluruh Jombang', '🔧 Sewa alat bangunan profesional'] as $item)
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:28px;height:28px;background:rgba(198,107,61,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0">
                    {{ substr($item, 0, 2) }}
                </div>
                <span style="font-size:13.5px;color:rgba(232,220,199,.55)">{{ substr($item, 3) }}</span>
            </div>
            @endforeach
        </div>

        {{-- Bottom branding --}}
        <div style="position:absolute;bottom:32px;left:56px;font-size:12px;color:rgba(232,220,199,.25)">
            © {{ date('Y') }} Sinar Alam, Jombang — Jawa Timur
        </div>
    </div>

    {{-- ── PANEL KANAN (form) ── --}}
    <div class="auth-panel-right">

        {{-- Back to home --}}
        <div style="margin-bottom:40px">
            <a href="{{ route('beranda') }}"
               style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--clay);text-decoration:none;font-weight:500;transition:color .2s"
               onmouseover="this.style.color='var(--terracotta)'"
               onmouseout="this.style.color='var(--clay)'">
                ← Kembali ke Beranda
            </a>
        </div>

        {{-- Flash messages --}}
        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:20px">✕ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:20px">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:20px">
                <ul style="margin:0;padding-left:18px">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Konten form --}}
        <div style="max-width:400px;width:100%">
            @yield('content')
        </div>

    </div>

</div>

@stack('scripts')
</body>
</html>
