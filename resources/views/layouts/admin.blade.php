<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin Sinar Alam</title>
    
    @php
        $faviconPath = \App\Models\Pengaturan::get('favicon');
        $faviconVersion = \App\Models\Pengaturan::where('kunci', 'favicon')->first()?->updated_at?->timestamp ?? 'default';
        $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
    @endphp
    <link class="favicon-link" rel="icon" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,700&family=Epilogue:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bangunmart.css') }}">

    @stack('styles')
</head>
<body style="background:var(--oat)">

<div class="adm-layout">

    {{-- ── SIDEBAR ── --}}
    <aside class="adm-sidebar">
        <div class="adm-sb-hdr">
            <div class="adm-sb-logo" style="display:flex;align-items:center">
                <img src="{{ asset('gambar/logo-sinar-alam.png') }}"
         alt="Sinar Alam"
         style="height:48px;width:auto;display:block">
        </div>
            <div class="adm-sb-role">Panel Administrator</div>
        </div>

        <nav class="adm-nav">

            {{-- Utama --}}
            <div class="adm-nav-sec">
                <div class="adm-nav-lbl">Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="adm-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.pesanan.index') }}" class="adm-nav-item {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    Pesanan
                    @php
                        try { $pendingOrders = \App\Models\Pesanan::where('status','pending')->count(); }
                        catch (\Exception $e) { $pendingOrders = 0; }
                    @endphp
                    @if($pendingOrders > 0)
                        <span class="adm-nav-badge">{{ $pendingOrders }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="adm-nav-item {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    Pembayaran
                </a>
            </div>

            {{-- Inventaris --}}
            <div class="adm-nav-sec">
                <div class="adm-nav-lbl">Inventaris</div>
                <a href="{{ route('admin.produk.index') }}" class="adm-nav-item {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    Manajemen Produk
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="adm-nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Kategori
                </a>
                <a href="{{ route('admin.stok.index') }}" class="adm-nav-item {{ request()->routeIs('admin.stok.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    Stok & Gudang
                </a>
            </div>

            {{-- Layanan --}}
            <div class="adm-nav-sec">
                <div class="adm-nav-lbl">Layanan</div>
                <a href="{{ route('admin.sewa.index') }}" class="adm-nav-item {{ request()->routeIs('admin.sewa.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
                    Sewa Alat
                    @php
                        try { $sewaAktif = \App\Models\BookingAlat::where('status','aktif')->count(); }
                        catch (\Exception $e) { $sewaAktif = 0; }
                    @endphp
                    @if($sewaAktif > 0)
                        <span class="adm-nav-badge">{{ $sewaAktif }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.pengiriman.index') }}" class="adm-nav-item {{ request()->routeIs('admin.pengiriman.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M1 3h15v13H1zM16 8l6 2v5h-6z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    Pengiriman Armada
                </a>
                <a href="{{ route('admin.supplier.index') }}" class="adm-nav-item {{ request()->routeIs('admin.supplier.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                    Supplier
                </a>
            </div>

            {{-- Pemasaran --}}
            <div class="adm-nav-sec">
                <div class="adm-nav-lbl">Pemasaran</div>
                <a href="{{ route('admin.promo.index') }}" class="adm-nav-item {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    Promo & Voucher
                </a>
            </div>

            {{-- Sistem --}}
            <div class="adm-nav-sec">
                <div class="adm-nav-lbl">Sistem</div>
                <a href="{{ route('admin.users.index') }}" class="adm-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Manajemen User
                </a>
                <a href="{{ route('admin.activity-log') }}" class="adm-nav-item {{ request()->routeIs('admin.activity-log') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Activity Log
                </a>
                <a href="{{ route('admin.pengaturan') }}" class="adm-nav-item {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
                    Pengaturan
                </a>
            </div>

        </nav>

        {{-- User info --}}
        <div class="adm-sb-footer">
            <a href="{{ route('profil') }}" class="adm-sb-user" style="text-decoration:none">
                @if(auth()->user()->avatar)
                    <div style="width:34px;height:34px;border-radius:50%;overflow:hidden;flex-shrink:0;background:var(--terracotta)">
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ e(auth()->user()->name) }}" style="width:100%;height:100%;object-fit:cover">
                    </div>
                @else
                    <div class="adm-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                @endif
                <div>
                    <div class="adm-user-name" style="color:var(--sand)">{{ auth()->user()->name }}</div>
                    <div class="adm-user-role" style="color:rgba(232,220,199,.32)">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff' }}</div>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin-top:4px">
                @csrf
                <button type="submit" style="display:flex;align-items:center;gap:8px;width:100%;padding:7px 10px;background:transparent;border:none;cursor:pointer;border-radius:var(--r-sm);font-family:var(--fb);font-size:12px;color:rgba(232,220,199,.32);transition:all .2s" onmouseover="this.style.background='rgba(232,220,199,.06)';this.style.color='var(--terracotta)'" onmouseout="this.style.background='transparent';this.style.color='rgba(232,220,199,.32)'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ── KONTEN UTAMA ── --}}
    <div class="adm-main">

        {{-- Topbar --}}
        <div class="adm-topbar">
            {{-- Sidebar toggle (mobile) --}}
            <button class="adm-sidebar-toggle" id="admSidebarToggle" onclick="toggleAdmSidebar()" aria-label="Toggle sidebar">
                <span></span><span></span><span></span>
            </button>

            <div class="adm-topbar-title">@yield('page_title', 'Dashboard')</div>

            {{-- Breadcrumb --}}
            <div style="font-size:12.5px;color:var(--clay);flex:1;margin-left:12px">
                @yield('breadcrumb')
            </div>

            <span style="font-size:12.5px;color:var(--clay)">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>

            {{-- Notifikasi --}}
            <div style="position:relative;cursor:pointer" onclick="toggleNotif()">
                <div style="width:36px;height:36px;background:var(--oat);border:1.5px solid rgba(176,139,110,.18);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:18px">🔔</div>
                @php
                    try { $lowStock = \App\Models\Produk::where('stok', '<', 20)->where('aktif', true)->count(); }
                    catch (\Exception $e) { $lowStock = 0; }
                @endphp
                @if($lowStock > 0)
                    <div style="position:absolute;top:8px;right:8px;width:7px;height:7px;background:var(--terracotta);border-radius:50%;border:2px solid #fff"></div>
                @endif
            </div>

            <a href="{{ route('profil') }}" class="adm-avatar" style="cursor:pointer;text-decoration:none;overflow:hidden;background:var(--terracotta);display:flex;align-items:center;justify-content:center" title="{{ auth()->user()->name }}">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ e(auth()->user()->name) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                @endif
            </a>
        </div>

        {{-- Flash Messages --}}
        <div style="padding:0 28px;margin-top:16px">
            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">✕ {{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:18px">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Konten Halaman --}}
        <div class="adm-content">
            @yield('content')
        </div>

    </div>
</div>

{{-- Mobile Sidebar Overlay --}}
<div class="adm-overlay" id="admOverlay" onclick="toggleAdmSidebar()"></div>

@stack('scripts')
<script>
    function toggleNotif() {
        window.location.href = '{{ route('admin.stok.index') }}?filter=low_stock';
    }
    function toggleAdmSidebar() {
        const layout  = document.querySelector('.adm-layout');
        const overlay = document.getElementById('admOverlay');
        const sidebar = document.querySelector('.adm-sidebar');
        if (!layout) return;
        const open = layout.classList.toggle('sidebar-open');
        if (overlay) overlay.classList.toggle('active', open);
    }
</script>
</body>
</html>