<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'telepon', 'role',
        'password', 'avatar', 'aktif', 'google_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'aktif'             => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function alamat()
    {
        return $this->hasMany(AlamatUser::class);
    }

    public function alamatUtama()
    {
        return $this->hasOne(AlamatUser::class)->where('is_utama', true);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Produk::class, 'wishlists')->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(BookingAlat::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}