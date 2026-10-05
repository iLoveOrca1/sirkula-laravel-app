<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limbah extends Model
{
    use HasFactory;

    protected $table = 'laporan_limbah';
    protected $guarded = ['id'];

    // Relasi balik: Limbah ini milik siapa?
    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    // Relasi balik: Limbah ini masuk kategori apa?
    public function kategori()
    {
        return $this->belongsTo(KategoriLimbah::class, 'kategori_id');
    }
}