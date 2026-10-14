<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Users (Custom untuk Sirkula)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Digunakan untuk Nama Bisnis / Fasilitas pada form register baru
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Custom kolom
            $table->enum('role', ['admin', 'petugas', 'mitra', 'pembeli'])->default('mitra');
            $table->string('no_whatsapp')->nullable(); // Menampung input whatsapp
            $table->string('nama_instansi')->nullable();
            $table->string('jenis_usaha')->nullable(); // Ditambahkan untuk menampung kategori fasilitas/bisnis
            $table->text('alamat_lengkap')->nullable(); // Menampung Kota/Lokasi Fasilitas
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_verified')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tabel Password Reset (Bawaan Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Tabel Sessions (Bawaan Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
