<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\BeritaController;

/*
|--------------------------------------------------------------------------
| Halaman Statis
|--------------------------------------------------------------------------
*/
Route::view('/', 'home')->name('home');
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/kontak', 'kontak')->name('kontak');
Route::view('/profil', 'profil')->name('profil');

/*
|--------------------------------------------------------------------------
| Routes PRODUK - Menggunakan Resource Controller
|--------------------------------------------------------------------------
| Ini akan otomatis membuat routes:
| GET    /produk              -> index()   (Daftar produk)
| GET    /produk/create       -> create()  (Form tambah)
| POST   /produk              -> store()   (Simpan produk)
| GET    /produk/{id}         -> show()    (Detail produk)
| GET    /produk/{id}/edit    -> edit()    (Form edit)
| PUT    /produk/{id}         -> update()  (Update produk)
| DELETE /produk/{id}         -> destroy() (Hapus produk)
*/
Route::resource('produk', ProdukController::class);

/*
|--------------------------------------------------------------------------
| Routes BERITA - Menggunakan Resource Controller
|--------------------------------------------------------------------------
*/
Route::resource('berita', BeritaController::class);