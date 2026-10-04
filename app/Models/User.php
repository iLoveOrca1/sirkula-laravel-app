<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id']; // Membuka akses mass assignment selain ID

    // Relasi: Satu mitra memiliki banyak listing limbah di Showcase
    public function limbahs()
    {
        return $this->hasMany(Limbah::class, 'mitra_id');
    }

    // Relasi: Satu mitra memiliki banyak laporan private
    public function laporanLimbahs()
    {
        return $this->hasMany(LaporanLimbah::class, 'mitra_id');
    }

    // Helper function untuk cek role dengan mudah nanti
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}