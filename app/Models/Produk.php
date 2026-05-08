<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    
    protected $table = 'produks';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'kategori',
        'deskripsi',
        'foto',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
        'stok' => 'integer',
    ];
  
    protected $hidden = [];
    
    public $timestamps = true;
}