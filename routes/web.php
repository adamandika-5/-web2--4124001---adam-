<?php

use Illuminate\Support\Facades\Route;

 

Route::get('/', function () {

    return view('welcome');

});

 

// Route baru – wajib ditambahkan!

Route::get('/perkenalan', function () {

    return '<h1>Halo! Nama saya [Nama Anda]</h1>

            <p>NIM: [NIM Anda] | Prodi: Sistem Informasi</p>

            <p>Saya siap belajar Laravel! 🚀</p>';

});