<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriLimbah;

class KategoriLimbahSeeder extends Seeder
{
    public function run(): void
    {
        KategoriLimbah::insert([
            [
                'nama_kategori' => 'Limbah Organik',
                'slug' => 'limbah-organik',
                'badge_color' => 'success',
                'deskripsi' => 'Limbah organik terurai hayati seperti sisa makanan, daun, dan ampas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Limbah Anorganik',
                'slug' => 'limbah-anorganik',
                'badge_color' => 'secondary',
                'deskripsi' => 'Limbah anorganik kering seperti kardus, plastik, dan botol.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Limbah B3',
                'slug' => 'limbah-b3',
                'badge_color' => 'danger',
                'deskripsi' => 'Bahan Berbahaya dan Beracun yang memerlukan penanganan khusus.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}