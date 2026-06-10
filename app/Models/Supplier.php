<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model {
    use SoftDeletes;
    protected $fillable = ['nama','kontak','telepon','email','alamat','kota','catatan','aktif'];
    protected $casts    = ['aktif' => 'boolean'];
    public function produk() { return $this->hasMany(Produk::class); }
    public function barangSupplier() { return $this->hasMany(ProdukSupplier::class); }
}