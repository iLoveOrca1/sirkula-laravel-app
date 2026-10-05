<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLimbah extends Model
{
    use HasFactory;

    protected $table = 'kategori_limbah'; // Memaksa nama tabel agar tidak jadi kategori_limbahs
    protected $guarded = ['id'];

    public function limbahs()
    {
        return $this->hasMany(Limbah::class, 'kategori_id');
    }
}