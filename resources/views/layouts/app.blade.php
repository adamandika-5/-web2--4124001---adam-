<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Bangunan')</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    
    {{-- Navbar --}}
    <nav class="bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-xl font-bold">Sinar Alam</a>
                    
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}" class="hover:text-teal-200 transition">Home</a>
                        <a href="{{ route('tentang') }}" class="hover:text-teal-200 transition">Tentang</a>
                        <a href="{{ route('produk.index') }}" class="hover:text-teal-200 transition">Produk</a>
                        <a href="{{ route('kontak') }}" class="hover:text-teal-200 transition">Kontak</a>
                    </div>
                </div>
                
                <a href="{{ route('produk.create') }}" 
                   class="bg-white text-teal-700 px-4 py-2 rounded-lg hover:bg-teal-50 transition hidden md:block">
                    + Tambah Produk
                </a>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2026 Sinar Alam - Toko Bangunan Terpercaya</p>
        </div>
    </footer>

</body>
</html>