<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::view('/', 'home')->name('home');
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/kontak', 'kontak')->name('kontak');
Route::view('/profil', 'profil')->name('profil');

/*
|--------------------------------------------------------------------------
| DATA PRODUK
|--------------------------------------------------------------------------
*/

$produkList = [

    1 => [
        'id' => 1,
        'nama' => 'Semen Tiga Roda',
        'kategori' => 'Semen',
        'badge' => 'teal',
        'harga' => 72000,
        'stok' => 150,
        'deskripsi' => 'Semen berkualitas tinggi untuk pondasi dan dinding bangunan.',
        'gambar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1O3lw0anb3U_uuCtL7uHuNjUwKadZA67DkQ&s',
    ],

    2 => [
        'id' => 2,
        'nama' => 'Cat Dulux',
        'kategori' => 'Cat',
        'badge' => 'blue',
        'harga' => 185000,
        'stok' => 60,
        'deskripsi' => 'Cat premium untuk interior dan eksterior tahan lama.',
        'gambar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSLEkz8VV_uuHIi1y6UWv8qvP8z2Tb8922IRQ&s',
    ],

    3 => [
        'id' => 3,
        'nama' => 'Pasir Bangunan',
        'kategori' => 'Pasir',
        'badge' => 'yellow',
        'harga' => 250000,
        'stok' => 40,
        'deskripsi' => 'Pasir pilihan untuk campuran beton dan plester berkualitas.',
        'gambar' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1200&auto=format&fit=crop',
    ],

];

/*
|--------------------------------------------------------------------------
| PRODUK INDEX
|--------------------------------------------------------------------------
*/

Route::get('/produk', function () use ($produkList) {

    $produk = array_values($produkList);

    return view('produk.index', compact('produk'));

})->name('produk.index');

/*
|--------------------------------------------------------------------------
| DETAIL PRODUK
|--------------------------------------------------------------------------
*/

Route::get('/produk/{id}', function ($id) use ($produkList) {

    if (!isset($produkList[$id])) {
        abort(404);
    }

    $produk = $produkList[$id];

    return view('produk.show', compact('produk'));

})->name('produk.show');

/*
|--------------------------------------------------------------------------
| EDIT PRODUK
|--------------------------------------------------------------------------
*/

Route::get('/produk/{id}/edit', function ($id) use ($produkList) {

    if (!isset($produkList[$id])) {
        abort(404);
    }

    $produk = $produkList[$id];

    return view('produk.edit', compact('produk'));

})->name('produk.edit');

/*
|--------------------------------------------------------------------------
| FORM TAMBAH PRODUK
|--------------------------------------------------------------------------
*/

Route::get('/produk/create', function () {

    return view('produk.create');

})->name('produk.create');

/*
|--------------------------------------------------------------------------
| SIMPAN PRODUK
|--------------------------------------------------------------------------
*/

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
        ->with('success', 'Produk berhasil ditambahkan.');

})->name('produk.store');

/*
|--------------------------------------------------------------------------
| HAPUS PRODUK
|--------------------------------------------------------------------------
*/

Route::delete('/produk/{id}', function ($id) {

    return redirect()
        ->route('produk.index')
        ->with('success', 'Produk berhasil dihapus.');

})->name('produk.destroy');

/*
|--------------------------------------------------------------------------
| BERITA
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| FORM TAMBAH BERITA
|--------------------------------------------------------------------------
*/

Route::get('/berita/create', function () {

    return view('berita.create');

})->name('berita.create');

/*
|--------------------------------------------------------------------------
| SIMPAN BERITA
|--------------------------------------------------------------------------
*/

Route::post('/berita', function () {

    request()->validate([
        'judul' => 'required|min:5',
        'kategori' => 'required|min:3',
        'penulis' => 'required|min:3',
        'isi' => 'required|min:20',
    ]);

    return redirect()
        ->route('berita.index')
        ->with('success', 'Berita berhasil dipublikasikan.');

})->name('berita.store');