<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanLimbah extends Model
{
    use HasFactory;

    protected $table = 'laporan_limbah';

    // Izinkan kolom-kolom ini diisi secara massal
    protected $fillable = [
        'mitra_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'estimasi_volume',
        'satuan',
        'tanggal_pencatatan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriLimbah::class, 'kategori_id');
    }
}