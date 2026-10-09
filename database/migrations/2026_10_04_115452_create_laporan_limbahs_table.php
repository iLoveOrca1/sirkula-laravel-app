<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_limbahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');

            // Relasi ke tabel kategori dengan nama plural (kategori_limbahs)
            $table->foreignId('kategori_id')->constrained('kategori_limbahs')->onDelete('cascade');

            $table->string('judul');
            $table->text('deskripsi');
            $table->decimal('estimasi_volume', 10, 2);
            $table->enum('satuan', ['kg', 'ton', 'liter', 'pcs']);
            $table->date('tanggal_pencatatan');

            // Alur status (huruf kecil semua):
            // menunggu -> tersedia -> sedang dijemput -> selesai   (atau: menunggu -> ditolak)
            $table->enum('status', ['menunggu', 'tersedia', 'ditolak', 'sedang dijemput', 'selesai'])
                ->default('menunggu');

            // Kolom foto_limbah untuk foto material
            $table->string('foto_limbah')->nullable();

            // Kolom koordinat lokasi peta
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_limbahs');
    }
};