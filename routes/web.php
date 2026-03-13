<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KatalogController;

/*
| Route Statis (Closure)
*/

Route::get('/tentang', function () {
    return "<h1>Halaman Tentang</h1>";
})->name('tentang.index');

Route::get('/kontak', function () {
    return "<h1>Halaman Kontak</h1>";
})->name('kontak.index');

Route::get('/layanan', function () {
    return "<h1>Halaman Layanan</h1>";
})->name('layanan.index');


/*
| Route Dinamis (Controller)
*/

Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');

Route::get('/profil/{nim}', [ProfilController::class, 'show'])->name('profil.show');

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');

Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');


