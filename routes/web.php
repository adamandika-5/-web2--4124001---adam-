<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route teman pertama
Route::get('/teman', function () {
    return '<h1>Halo! Nama saya Adam</h1>';
});

// Route teman kedua (Arel)
Route::get('/perkenalan-arel', function (): string {
    return '<h1>Halo! Nama saya Arel</h1>
            <p>NIM: 4124027</p>
            <p>Prodi: Sistem Informasi</p>
            <p>Saya siap belajar Laravel! 🚀</p>';
});