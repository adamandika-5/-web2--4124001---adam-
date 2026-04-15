<footer class="bg-slate-800 text-white mt-10">
    <div class="max-w-7xl mx-auto px-4 py-6 text-center">
        
        <p class="text-sm text-slate-300">
            &copy; {{ date('Y') }} Sinar Alam - Toko Bangunan. All rights reserved.
        </p>

        <div class="mt-4 flex flex-wrap justify-center gap-4 text-sm">
            <a href="{{ url('/') }}" class="hover:text-gray-300 transition">Home</a>
            <a href="{{ url('/tentang') }}" class="hover:text-gray-300 transition">Tentang</a>
            <a href="{{ url('/produk') }}" class="hover:text-gray-300 transition">Produk</a>
            <a href="{{ url('/kontak') }}" class="hover:text-gray-300 transition">Kontak</a>
            <a href="{{ url('/produk/create') }}" class="hover:text-gray-300 transition">Tambah Produk</a>
        </div>

    </div>
</footer>