<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanMaterial extends Model
{
    use HasFactory;

    protected $table = 'permintaan_materials';

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

    // Relasi: Permintaan material ini dibuat oleh User (Mitra/Pengepul) siapa?
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}