<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_material',
        'kategori',
        'volume',
        'satuan',
        'frekuensi',
        'lokasi',
        'deskripsi',
        'whatsapp',
    ];

    // Relasi: Satu permintaan dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}