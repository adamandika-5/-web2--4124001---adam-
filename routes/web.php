<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route /teman menampilkan Adam & Arel
Route::get('/teman', function () {
    return '
        <h1>Perkenalan Kelompok</h1>

        <h2>Halo! Nama saya Adam</h2>
        <p>NIM: 4124001</p>
        <p>Prodi: Sistem Informasi</p>

        <hr>

        <h2>Halo! Nama saya Arel</h2>
        <p>NIM: 4124027</p>
        <p>Prodi: Sistem Informasi</p>

        <p>Kami siap belajar Laravel! 🚀</p>

        <hr>Halo Arel ini Adam, kita akan belajar Laravel bersama-sama!
        yuk semangat belajar Laravel!
        kira-kira kita akan belajar apa saja ya di Laravel?
    ';
});