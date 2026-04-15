<nav class="bg-slate-800 text-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        
        <!-- Logo -->
        <a href="/" class="text-xl font-bold hover:text-gray-300 transition">
            Sinar Alam
        </a>

        <!-- Menu Desktop -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="/" class="hover:text-gray-300 transition">Home</a>
            <a href="/tentang" class="hover:text-gray-300 transition">Tentang</a>
            <a href="/produk" class="hover:text-gray-300 transition">Produk</a>
            <a href="/kontak" class="hover:text-gray-300 transition">Kontak</a>
        </div>

        <!-- CTA Desktop -->
        <a href="/produk/create"
           class="hidden md:inline-block bg-blue-500 px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            + Tambah Produk
        </a>

        <!-- Mobile Button -->
        <button type="button" id="menuBtn" class="md:hidden text-2xl">
            ☰
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden px-4 pb-4 space-y-2 bg-slate-700">
        <a href="/" class="block hover:text-gray-300 transition">Home</a>
        <a href="/tentang" class="block hover:text-gray-300 transition">Tentang</a>
        <a href="/produk" class="block hover:text-gray-300 transition">Produk</a>
        <a href="/kontak" class="block hover:text-gray-300 transition">Kontak</a>
        <a href="/produk/create"
           class="block bg-blue-500 px-4 py-2 rounded-lg hover:bg-blue-600 transition text-center">
            + Tambah Produk
        </a>
    </div>

    <script>
        document.getElementById('menuBtn').addEventListener('click', function () {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>
</nav>