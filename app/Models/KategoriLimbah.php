<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLimbah extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit agar terbaca plural
    protected $table = 'kategori_limbahs';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function laporanLimbahs()
    {
        return $this->hasMany(LaporanLimbah::class, 'kategori_id');
    }
}