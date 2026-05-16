<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model {
    protected $fillable = ['kunci','nilai','grup'];

    public static function get(string $kunci, mixed $default = null): mixed {
        return static::where('kunci', $kunci)->value('nilai') ?? $default;
    }

    public static function set(string $kunci, mixed $nilai, string $grup = 'umum'): void {
        static::updateOrCreate(
            ['kunci' => $kunci],
            ['nilai' => $nilai, 'grup' => $grup]
        );
    }
}