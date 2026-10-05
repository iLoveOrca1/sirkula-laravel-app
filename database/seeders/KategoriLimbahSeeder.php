<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\KategoriLimbah;

class KategoriLimbahSeeder extends Seeder
{
    public function run(): void
    {
        KategoriLimbah::insert([
            ['nama_kategori' => 'Limbah Organik', 'slug' => 'limbah-organik', 'badge_color' => 'success'],
            ['nama_kategori' => 'Limbah Anorganik', 'slug' => 'limbah-anorganik', 'badge_color' => 'secondary'],
            ['nama_kategori' => 'Limbah B3', 'slug' => 'limbah-b3', 'badge_color' => 'danger'],
        ]);
    }
}