<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_limbahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->nullable();
            $table->string('badge_color')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Seeding otomatis langsung saat tabel dibuat (tanpa perlu db:seed terpisah)
        DB::table('kategori_limbahs')->insert([
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

    public function down(): void
    {
        Schema::dropIfExists('kategori_limbahs');
    }
};