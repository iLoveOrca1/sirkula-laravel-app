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
     * Semua kolom yang disimpan oleh ProfileController::update() harus ada di sini,
     * kalau tidak, nilainya akan dibuang diam-diam tanpa error.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nama_instansi',
        'no_whatsapp',
        'alamat_lengkap',
        'kelurahan',
        'kode_pos',
        'latitude',
        'longitude',
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
    public function laporanLimbahs()
    {
        return $this->hasMany(LaporanLimbah::class, 'mitra_id');
    }

    // Helper function untuk cek role dengan mudah
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}