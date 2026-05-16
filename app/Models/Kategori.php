<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $fillable = [
        'nama', 'slug', 'ikon', 'warna',
        'deskripsi', 'aktif', 'urutan',
    ];

    protected $casts = ['aktif' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

    public function subKategori()
    {
        return $this->hasMany(SubKategori::class);
    }

    public function getProdukCountAttribute(): int
    {
        return $this->produk()->where('aktif', true)->count();
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true)->orderBy('urutan');
    }
}