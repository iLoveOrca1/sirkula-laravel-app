<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanLimbah extends Model
{
    use HasFactory;

    // Perbaiki nama tabel menjadi 'laporan_limbahs' sesuai migrasi
    protected $table = 'laporan_limbahs';

    // Kolom-kolom yang diizinkan untuk diisi dari form (termasuk latitude & longitude)
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
        'latitude',   // <-- Ditambahkan agar bisa disimpan
        'longitude',  // <-- Ditambahkan agar bisa disimpan
    ];

    // Relasi balik: Laporan ini milik Mitra (User) siapa?
    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    // Relasi balik: Laporan ini masuk Kategori apa?
    public function kategori()
    {
        return $this->belongsTo(KategoriLimbah::class, 'kategori_id');
    }
}