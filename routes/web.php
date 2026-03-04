<?php

use Illuminate\Support\Facades\Route;

 

Route::get('/', function () {

    return view('welcome');

});

 

// Route baru – wajib ditambahkan!

Route::get('/perkenalan', function () {

    return '<h1>Halo! Nama saya [Adam Andika Sukma]</h1>

            <p>NIM: [4124001] | Prodi: Sistem Informasi</p>

            <p>Saya siap belajar Laravel! 🚀</p>';

});
