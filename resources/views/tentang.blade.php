@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

<!-- HERO -->
<section class="bg-slate-800 text-white py-16 rounded-lg">
    <div class="text-center px-6">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            SINAR ALAM
        </h1>
        <p class="text-gray-300 max-w-2xl mx-auto">
            Kami menyediakan berbagai kebutuhan material bangunan berkualitas tinggi untuk mendukung proyek Anda.
        </p>
    </div>
</section>

<!-- DESKRIPSI -->
<section class="mt-12">
    <div class="bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Siapa Kami?</h2>
        <p class="text-gray-600 leading-relaxed">
            Toko Bangunan kami telah berdiri sejak tahun 2020 dan melayani berbagai kebutuhan konstruksi mulai dari skala kecil hingga besar. 
            Kami menyediakan produk seperti semen, pasir, batu, cat, hingga alat-alat bangunan dengan kualitas terbaik.
        </p>

        <p class="text-gray-600 mt-4 leading-relaxed">
            Dengan pengalaman dan pelayanan terbaik, kami siap membantu Anda dalam memenuhi kebutuhan material bangunan dengan harga terjangkau.
        </p>
    </div>
</section>

<!-- KEUNGGULAN -->
<section class="mt-12">
    <h2 class="text-2xl font-bold text-center mb-6">Keunggulan Kami</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg">
            <h3 class="font-semibold text-lg mb-2">Produk Berkualitas</h3>
            <p class="text-gray-600 text-sm">
                Semua material telah terjamin kualitasnya dan berasal dari supplier terpercaya.
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg">
            <h3 class="font-semibold text-lg mb-2">Harga Terjangkau</h3>
            <p class="text-gray-600 text-sm">
                Kami menawarkan harga kompetitif untuk semua kalangan.
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg">
            <h3 class="font-semibold text-lg mb-2">Pelayanan Cepat</h3>
            <p class="text-gray-600 text-sm">
                Respon cepat dan siap melayani kebutuhan Anda setiap saat.
            </p>
        </div>

    </div>
</section>

<!-- VISI MISI -->
<section class="mt-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-blue-500 text-white p-6 rounded-lg">
            <h3 class="text-xl font-bold mb-2">Visi</h3>
            <p>
                Menjadi toko bangunan terpercaya dan terlengkap di Indonesia.
            </p>
        </div>

        <div class="bg-slate-800 text-white p-6 rounded-lg">
            <h3 class="text-xl font-bold mb-2">Misi</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li>Menyediakan produk berkualitas</li>
                <li>Memberikan pelayanan terbaik</li>
                <li>Menjaga kepercayaan pelanggan</li>
            </ul>
        </div>

    </div>
</section>

@endsection