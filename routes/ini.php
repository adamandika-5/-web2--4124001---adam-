<?php

use Illuminate\Support\Facades\Route;

 

Route::get('/', function () {

    return view('welcome');

});

 

// Route baru – wajib ditambahkan!

Route::get('/teman', function () {

    return '<h1>Halo! Nama saya [arel]</h1>

            <p>NIM: [4124027] | Prodi: Sistem Informasi</p>

            <p>Saya siap belajar Laravel! 🚀</p>';

});