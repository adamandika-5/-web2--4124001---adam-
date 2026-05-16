<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Produk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori_id', 'sub_kategori_id', 'supplier_id',
        'nama', 'slug', 'sku', 'ikon', 'warna_bg',
        'deskripsi', 'spesifikasi',
        'harga', 'harga_promo', 'stok', 'satuan', 'berat',
        'jenis_pengiriman', 'aktif', 'unggulan', 'terjual',
    ];

    protected $casts = [
        'aktif'      => 'boolean',
        'unggulan'   => 'boolean',
        'harga'      => 'decimal:2',
        'harga_promo'=> 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama) . '-' . uniqid();
            }
        });
    }

    /* ── Relasi ── */
    public function kategori()     { return $this->belongsTo(Kategori::class); }
    public function subKategori()  { return $this->belongsTo(SubKategori::class); }
    public function supplier()     { return $this->belongsTo(Supplier::class); }
    public function gambar()       { return $this->hasMany(ProdukGambar::class)->orderBy('urutan'); }
    public function gambarUtama()  { return $this->hasOne(ProdukGambar::class)->where('is_utama', true); }
    public function pesananItems() { return $this->hasMany(PesananItem::class); }
    public function wishlistUsers(){ return $this->belongsToMany(User::class, 'wishlists')->withTimestamps(); }

    /* ── Accessor ── */
    public function getGambarUtamaAttribute(): ?string
    {
        return $this->relationLoaded('gambar')
            ? $this->gambar->firstWhere('is_utama', true)?->path
            : $this->gambarUtama()->value('path');
    }

    public function getHargaFinalAttribute(): float
    {
        return (float) ($this->harga_promo ?? $this->harga);
    }

    public function getDiskonPersenAttribute(): int
    {
        if (!$this->harga_promo) return 0;
        return (int) round((($this->harga - $this->harga_promo) / $this->harga) * 100);
    }

    public function getStokStatusAttribute(): string
    {
        if ($this->stok <= 0)  return 'habis';
        if ($this->stok < 20)  return 'rendah';
        return 'tersedia';
    }

    /* ── Scopes ── */
    public function scopeAktif($q)     { return $q->where('aktif', true); }
    public function scopeUnggulan($q)  { return $q->where('unggulan', true); }
    public function scopeStokAda($q)   { return $q->where('stok', '>', 0); }
    public function scopeStokRendah($q){ return $q->where('stok', '>', 0)->where('stok', '<', 20); }
}