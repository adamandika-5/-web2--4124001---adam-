<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukSupplier extends Model
{
    protected $table = 'produk_suppliers';

    protected $fillable = [
        'supplier_id',
        'produk_id',
        'harga_beli',
        'satuan',
        'minimal_pembelian',
        'lead_time_hari',
        'catatan',
        'aktif',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'aktif'      => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
