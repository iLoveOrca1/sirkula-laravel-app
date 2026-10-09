<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <-- Sangat aman ditambahkan di sini
    ];

    /**
     * Kolom yang akan disembunyikan saat data User diubah menjadi Array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data otomatis (Casting).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: Satu mitra memiliki banyak laporan limbah
    // (Fungsi limbahs() yang error sudah dihapus, cukup gunakan ini saja)
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