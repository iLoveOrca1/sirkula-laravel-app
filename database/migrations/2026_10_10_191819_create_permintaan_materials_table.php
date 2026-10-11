<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_materials', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Mitra/Pengepul yang meminta)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Data Utama Permintaan
            $table->string('nama_material');
            $table->string('kategori'); // Organik, Anorganik Kertas, Minyak Jelantah, dll.
            $table->decimal('volume', 10, 2);
            $table->string('satuan'); // KG, Ton, Liter, Unit
            $table->string('frekuensi'); // Harian, Mingguan, Bulanan, Satu Kali Saja
            $table->string('lokasi'); // Lokasi pengambilan
            $table->text('deskripsi')->nullable();
            $table->string('whatsapp'); // Nomor kontak
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_materials');
    }
};