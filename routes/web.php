<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/kontak', 'kontak')->name('kontak');
Route::view('/profil', 'profil')->name('profil');

Route::get('/produk', function () {
    $produk = [
        [
            'nama' => 'Semen Tiga Roda',
            'kategori' => 'Semen',
            'badge' => 'teal',
            'harga' => 72000,
            'stok' => 150,
            'deskripsi' => 'Semen berkualitas tinggi untuk pondasi dan dinding bangunan.',
            'gambar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1O3lw0anb3U_uuCtL7uHuNjUwKadZA67DkQ&s',
        ],
        [
            'nama' => 'Cat Dulux',
            'kategori' => 'Cat',
            'badge' => 'blue',
            'harga' => 185000,
            'stok' => 60,
            'deskripsi' => 'Cat premium untuk interior dan eksterior tahan lama.',
            'gambar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSLEkz8VV_uuHIi1y6UWv8qvP8z2Tb8922IRQ&s',
        ],
        [
            'nama' => 'Pasir Bangunan',
            'kategori' => 'Pasir',
            'badge' => 'yellow',
            'harga' => 250000,
            'stok' => 40,
            'deskripsi' => 'Pasir pilihan untuk campuran beton dan plester berkualitas.',
            'gambar' => 'data:image/jpeg;base64,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',
        ],
    ];

    return view('produk.index', compact('produk'));
})->name('produk.index');

Route::get('/produk/create', function () {
    return view('produk.create');
})->name('produk.create');

Route::post('/produk', function () {
    request()->validate([
        'nama' => 'required|min:3',
        'kategori' => 'required|min:3',
        'harga' => 'required|numeric|min:1',
        'stok' => 'required|integer|min:0',
        'deskripsi' => 'required|min:10',
    ]);

    return redirect()
        ->route('produk.index')
        ->with('success', 'Produk toko bangunan berhasil ditambahkan.');
})->name('produk.store');

Route::get('/berita', function () {
    $berita = [
        [
            'judul' => 'Promo Semen dan Cat Minggu Ini',
            'kategori' => 'Promo',
            'badge' => 'green',
            'tanggal' => '15 Apr 2026',
            'ringkasan' => 'Diskon spesial untuk pembelian semen dan cat tertentu selama persediaan masih ada.',
            'gambar' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=1200&auto=format&fit=crop',
        ],
        [
            'judul' => 'Tips Memilih Material Bangunan Berkualitas',
            'kategori' => 'Tips',
            'badge' => 'purple',
            'tanggal' => '14 Apr 2026',
            'ringkasan' => 'Pelajari cara memilih semen, pasir, dan cat yang tepat untuk proyek rumah Anda.',
            'gambar' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1200&auto=format&fit=crop',
        ],
        [
            'judul' => 'Stok Pasir dan Batu Baru Sudah Tersedia',
            'kategori' => 'Informasi',
            'badge' => 'blue',
            'tanggal' => '13 Apr 2026',
            'ringkasan' => 'Kini tersedia stok pasir dan batu baru dengan kualitas lebih baik dan harga bersaing.',
            'gambar' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?q=80&w=1200&auto=format&fit=crop',
        ],
    ];

    return view('berita.index', compact('berita'));
})->name('berita.index');

Route::get('/berita/create', function () {
    return view('berita.create');
})->name('berita.create');

Route::post('/berita', function () {
    request()->validate([
        'judul' => 'required|min:5',
        'kategori' => 'required|min:3',
        'penulis' => 'required|min:3',
        'isi' => 'required|min:20',
    ]);

    return redirect()
        ->route('berita.index')
        ->with('success', 'Berita toko bangunan berhasil dipublikasikan.');
})->name('berita.store');