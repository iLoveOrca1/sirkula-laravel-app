<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanLimbah extends Model
{
    use HasFactory;

    protected $table = 'laporan_limbahs';

    protected $fillable = [
        'mitra_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'estimasi_volume',
        'satuan',
        'tanggal_pencatatan',
        'status',
        'foto_limbah',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pencatatan' => 'date',
        ];
    }

    // Relasi ke pemilik laporan (mitra)
    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    // Alias agar kode lama yang memanggil ->user tetap berjalan
    public function user()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    // Relasi ke kategori limbah
    public function kategori()
    {
        return $this->belongsTo(KategoriLimbah::class, 'kategori_id');
    }
}