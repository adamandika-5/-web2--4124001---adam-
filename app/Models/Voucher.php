<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model {
    protected $fillable = [
        'kode','nama','tipe','nilai','min_belanja','maks_diskon',
        'kuota','terpakai','maks_per_user','aktif','berlaku_mulai','berlaku_sampai',
    ];
    protected $casts = [
        'aktif'          => 'boolean',
        'berlaku_mulai'  => 'datetime',
        'berlaku_sampai' => 'datetime',
        'nilai'          => 'decimal:2',
        'min_belanja'    => 'decimal:2',
        'maks_diskon'    => 'decimal:2',
    ];

    public function hitungDiskon(float $subtotal): float {
        $minBelanja = (float) ($this->min_belanja ?? 0);
        if ($subtotal < $minBelanja) return 0;

        $diskon = strtolower($this->tipe) === 'persentase'
            ? $subtotal * ((float) $this->nilai / 100)
            : (float) $this->nilai;

        $maksDisk = $this->maks_diskon ? (float) $this->maks_diskon : null;
        return $maksDisk ? min($diskon, $maksDisk) : $diskon;
    }

    public function scopeValid($q) {
        return $q->where('aktif', true)
            ->where(fn($x) => $x->whereNull('kuota')->orWhereRaw('terpakai < kuota'))
            ->where(fn($x) => $x->whereNull('berlaku_mulai')->orWhere('berlaku_mulai','<=',now()))
            ->where(fn($x) => $x->whereNull('berlaku_sampai')->orWhere('berlaku_sampai','>=',now()));
    }
}