<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    protected $fillable = ['kategori_id', 'nama', 'slug', 'aktif'];
    protected $casts    = ['aktif' => 'boolean'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}