<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'beritas';

    protected $fillable = [
        'judul',
        'isi',
        'penulis',
        'kategori',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public $timestamps = true;
}