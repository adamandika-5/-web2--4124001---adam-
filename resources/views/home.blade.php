@extends('layouts.app')

@section('title', 'Home - Toko Bangunan')

@section('content')

<!-- HERO -->
<section class="bg-slate-800 text-white py-16 rounded-lg">
    <div class="text-center px-6">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Toko Bangunan Terlengkap 
        </h1>
        <p class="text-gray-300 mb-6 max-w-2xl mx-auto">
            Menyediakan berbagai kebutuhan material bangunan berkualitas dengan harga terjangkau.
        </p>
        <a href="/produk" class="bg-blue-500 px-6 py-3 rounded-lg hover:bg-blue-600">
            Lihat Produk
        </a>
    </div>
</section>

<!-- KATEGORI -->
<section class="mt-12">
    <h2 class="text-2xl font-bold mb-6 text-center">Kategori Produk</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg">
            <h3 class="font-semibold text-lg">Semen</h3>
            <p class="text-gray-600 text-sm">Berbagai merek semen berkualitas</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg">
            <h3 class="font-semibold text-lg">Pasir & Batu</h3>
            <p class="text-gray-600 text-sm">Material dasar konstruksi</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg">
            <h3 class="font-semibold text-lg">Cat</h3>
            <p class="text-gray-600 text-sm">Cat tembok & kayu berbagai warna</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg">
            <h3 class="font-semibold text-lg">Alat Bangunan</h3>
            <p class="text-gray-600 text-sm">Peralatan tukang lengkap</p>
        </div>

    </div>
</section>

<!-- CTA -->
<section class="mt-16 bg-blue-500 text-white py-10 rounded-lg text-center">
    <h2 class="text-2xl font-bold mb-3">
        Butuh Material Bangunan?
    </h2>
    <p class="mb-4">Hubungi kami sekarang untuk pemesanan!</p>
    <a href="/kontak" class="bg-white text-blue-500 px-6 py-2 rounded hover:bg-gray-200">
        Hubungi Kami
    </a>
</section>

@endsection