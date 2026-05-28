<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sinar Alam') — Toko Material Bangunan Terpercaya</title>
    <meta name="description" content="@yield('meta_desc', 'Sinar Alam menyediakan material bangunan berkualitas seperti semen, pasir, batu, besi, keramik, cat, pipa, dan sewa alat bangunan untuk kebutuhan renovasi dan konstruksi di Jombang.')">
    
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

    <nav class="pub-nav">
        <div class="pub-nav-inner">

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="nav-logo" style="display:flex;align-items:center;text-decoration:none">
                <img src="{{ asset('gambar/logo-sinar-alam.png') }}"
                     alt="Sinar Alam"
                     style="height:46px;width:auto;display:block">
            </a>

            {{-- Menu (desktop) --}}
            <div class="nav-links">
                <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="{{ request()->routeIs('katalog.*') ? 'active' : '' }}">Katalog</a>
                <a href="{{ route('sewa.index') }}" class="{{ request()->routeIs('sewa.*') ? 'active' : '' }}">Sewa Alat</a>
                <a href="{{ route('promo') }}" class="{{ request()->routeIs('promo') ? 'active' : '' }}">Promo</a>
                <a href="{{ route('lacak') }}" class="{{ request()->routeIs('lacak') ? 'active' : '' }}">Lacak Pesanan</a>
            </div>

            {{-- Search --}}
            <form action="{{ route('katalog.index') }}" method="GET" class="nav-search">
                <svg class="nav-search-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="9" r="6"/><path d="M15 15l3 3"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari semen, besi, cat...">
            </form>

            {{-- Actions --}}
            <div class="nav-actions">
                {{-- Keranjang --}}
                @if(!auth()->check() || auth()->user()->role === 'user')
                <a href="{{ route('keranjang.index') }}" class="nav-cart-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--clay)" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    @php
                        try {
                            $cartCount = class_exists('\Cart') ? \Cart::getTotalQuantity() : 0;
                        } catch (\Throwable $e) {
                            $cartCount = 0;
                        }
                    @endphp
                    @if($cartCount > 0)
                        <div class="nav-cart-count">{{ $cartCount }}</div>
                    @endif
                </a>
                @endif

                {{-- Auth --}}
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @else
                    <div class="nav-user-dropdown" style="position:relative">
                        <button onclick="toggleDropdown()" style="display:flex;align-items:center;gap:8px;background:var(--oat);border:1.5px solid var(--sand);border-radius:var(--r-md);padding:6px 12px;cursor:pointer;font-family:var(--fb);font-size:13px;font-weight:600;color:var(--soil)">
                            @if(auth()->user()->avatar)
                                <div style="width:26px;height:26px;border-radius:50%;overflow:hidden;flex-shrink:0;background:var(--terracotta)">
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ e(auth()->user()->name) }}" style="width:100%;height:100%;object-fit:cover">
                                </div>
                            @else
                                <div class="adm-avatar" style="width:26px;height:26px;font-size:11px">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="nav-user-name">{{ auth()->user()->name }}</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--clay)" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                        <div id="userDropdown" style="display:none;position:absolute;right:0;top:calc(100%+8px);background:#fff;border:1px solid var(--sand);border-radius:var(--r-md);box-shadow:var(--sh-md);min-width:160px;overflow:hidden;z-index:200">
                            @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                                <a href="{{ route('admin.dashboard') }}" style="display:block;padding:10px 16px;font-size:13px;color:var(--soil);text-decoration:none;border-bottom:1px solid var(--sand)" onmouseover="this.style.background='var(--oat)'" onmouseout="this.style.background='#fff'">📊 Dashboard Admin</a>
                            @elseif(auth()->user()->role === 'staff')
                                <a href="{{ route('admin.dashboard') }}" style="display:block;padding:10px 16px;font-size:13px;color:var(--soil);text-decoration:none;border-bottom:1px solid var(--sand)" onmouseover="this.style.background='var(--oat)'" onmouseout="this.style.background='#fff'">📋 Panel Staff</a>
                            @endif
                            <a href="{{ route('profil') }}" style="display:block;padding:10px 16px;font-size:13px;color:var(--soil);text-decoration:none;border-bottom:1px solid var(--sand)" onmouseover="this.style.background='var(--oat)'" onmouseout="this.style.background='#fff'">👤 Profil Saya</a>
                            @if(auth()->user()->role === 'user')
                                <a href="{{ route('pesanan.index') }}" style="display:block;padding:10px 16px;font-size:13px;color:var(--soil);text-decoration:none;border-bottom:1px solid var(--sand)" onmouseover="this.style.background='var(--oat)'" onmouseout="this.style.background='#fff'">📦 Pesanan Saya</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" style="width:100%;text-align:left;padding:10px 16px;font-size:13px;color:#c03030;background:none;border:none;cursor:pointer;font-family:var(--fb)" onmouseover="this.style.background='rgba(192,48,48,.05)'" onmouseout="this.style.background='none'">🚪 Keluar</button>
                            </form>
                        </div>
                    </div>
                @endguest

                {{-- Hamburger (mobile only) --}}
                <button class="nav-hamburger" id="navHamburger" onclick="toggleMobileNav()" aria-label="Buka menu">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>

        {{-- Mobile Nav Drawer --}}
        <div class="nav-mobile-drawer" id="navMobileDrawer">
            <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">🏠 Beranda</a>
            <a href="{{ route('katalog.index') }}" class="{{ request()->routeIs('katalog.*') ? 'active' : '' }}">🧱 Katalog Produk</a>
            <a href="{{ route('sewa.index') }}" class="{{ request()->routeIs('sewa.*') ? 'active' : '' }}">🔧 Sewa Alat</a>
            <a href="{{ route('promo') }}" class="{{ request()->routeIs('promo') ? 'active' : '' }}">🏷️ Promo</a>
            <a href="{{ route('lacak') }}" class="{{ request()->routeIs('lacak') ? 'active' : '' }}">📍 Lacak Pesanan</a>
            <div class="nav-mobile-search">
                <form action="{{ route('katalog.index') }}" method="GET" style="display:flex;gap:8px">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="form-inp" style="flex:1;font-size:13px;padding:9px 12px">
                    <button type="submit" class="btn btn-primary btn-sm" style="flex-shrink:0">Cari</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ── FLASH MESSAGES ── --}}
    @if(session('success'))
        <div style="max-width:1280px;margin:14px auto;padding:0 48px">
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div style="max-width:1280px;margin:14px auto;padding:0 48px">
            <div class="alert alert-error">✕ {{ session('error') }}</div>
        </div>
    @endif

    {{-- ── MAIN CONTENT ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── FOOTER ── --}}
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <div style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--sand);margin-bottom:10px">Sinar Alam</div>
                    <div style="font-size:13px;line-height:1.75;color:rgba(232,220,199,.42);max-width:270px">
                        Toko material bangunan terpercaya di Jombang, Jawa Timur. Melayani kebutuhan proyek skala rumahan hingga komersial dengan produk material berkualitas dan layanan sewa alat bangunan.
                    </div>
                    <div style="margin-top:18px;font-size:13px;color:rgba(232,220,199,.35);line-height:1.8">
                        📍 Jl. Brawijaya No.74/203, Peterongan, Kec. Peterongan, Kabupaten Jombang, Jawa Timur 61481<br>
                        📞 (0343) 555-1234<br>
                        ✉️ info@sinaralam.id
                    </div>
                </div>
                <div>
                    <div style="font-size:12px;font-weight:700;color:var(--sand);margin-bottom:14px;letter-spacing:.06em;text-transform:uppercase">Produk</div>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                        <li><a href="{{ url('/katalog?kategori=material-dasar') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Material Dasar</a></li>
                        <li><a href="{{ url('/katalog?kategori=besi-baja') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Besi & Baja</a></li>
                        <li><a href="{{ url('/katalog?kategori=atap-plafon') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Atap & Plafon</a></li>
                        <li><a href="{{ url('/katalog?kategori=keramik-granit') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Keramik & Granit</a></li>
                        <li><a href="{{ url('/katalog?kategori=cat-finishing') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Cat & Finishing</a></li>
                        <li><a href="{{ url('/katalog?kategori=kayu-triplek') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Kayu & Triplek</a></li>
                    </ul>
                </div>
                <div>
                    <div style="font-size:12px;font-weight:700;color:var(--sand);margin-bottom:14px;letter-spacing:.06em;text-transform:uppercase">Layanan</div>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                        <li><a href="{{ route('sewa.index') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Sewa Alat Bangunan</a></li>
                        <li><a href="{{ route('lacak') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Lacak Pesanan</a></li>
                        <li><a href="{{ route('kontak') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Konsultasi Material</a></li>
                        <li><a href="{{ route('promo') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Promo & Diskon</a></li>
                    </ul>
                </div>
                <div>
                    <div style="font-size:12px;font-weight:700;color:var(--sand);margin-bottom:14px;letter-spacing:.06em;text-transform:uppercase">Perusahaan</div>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:9px">
                        <li><a href="{{ route('tentang-kami') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Tentang Kami</a></li>
                        <li><a href="{{ route('kebijakan-privasi') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('syarat-ketentuan') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('kontak') }}" style="font-size:13px;color:rgba(232,220,199,.42);text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='rgba(232,220,199,.42)'">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} Sinar Alam. Hak cipta dilindungi undang-undang.</span>
                <span>Dibuat dengan ❤ di Jombang, Jawa Timur</span>
            </div>
        </div>
    </footer>

    {{-- WhatsApp Float --}}
    <a href="https://wa.me/{{ config('app.whatsapp_number', '625749503756') }}" target="_blank" class="wa-float" title="Chat WhatsApp Sinar Alam">💬</a>

    <script>
        function toggleDropdown() {
            const d = document.getElementById('userDropdown');
            d.style.display = d.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-user-dropdown')) {
                const d = document.getElementById('userDropdown');
                if (d) d.style.display = 'none';
            }
        });
        function toggleMobileNav() {
            const drawer = document.getElementById('navMobileDrawer');
            const btn    = document.getElementById('navHamburger');
            if (!drawer) return;
            const open = drawer.classList.toggle('open');
            btn.classList.toggle('open', open);
        }
        // Close drawer on link click
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#navMobileDrawer a').forEach(a => {
                a.addEventListener('click', () => {
                    document.getElementById('navMobileDrawer').classList.remove('open');
                    document.getElementById('navHamburger').classList.remove('open');
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>