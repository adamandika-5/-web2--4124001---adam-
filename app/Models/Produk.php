<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Nama tabel
    protected $table = 'produks';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'kategori',
        'deskripsi',
        'gambar',
        'aktif',
    ];

    // Casting tipe data
    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
        'stok' => 'integer',
    ];

    protected $hidden = [];

    public $timestamps = true;
}