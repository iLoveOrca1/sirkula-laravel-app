<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nama kolom di sini HARUS sama dengan yang dipakai controller & view:
     * mitra_id, judul, estimasi_volume, foto_limbah, tanggal_pencatatan, dst.
     */
    public function up(): void
    {
        Schema::create('laporan_limbahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_limbahs')->onDelete('cascade');

            $table->string('judul');
            $table->text('deskripsi');
            $table->decimal('estimasi_volume', 10, 2);
            $table->string('satuan', 20); // kg, ton, liter, pcs
            $table->date('tanggal_pencatatan');

            // String (bukan enum) agar semua status aplikasi bisa tersimpan:
            // menunggu, tersedia, ditolak, sedang diproses, sudah diambil
            $table->string('status', 50)->default('menunggu');

            $table->string('foto_limbah')->nullable();

            // Koordinat lokasi limbah
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_limbahs');
    }
};