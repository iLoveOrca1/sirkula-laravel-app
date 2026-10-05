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
        Schema::create('laporan_limbah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_limbah')->onDelete('cascade');
            $table->date('tanggal_pencatatan');
            $table->decimal('volume_dihasilkan', 10, 2);
            $table->string('satuan');
            $table->string('pihak_pengambil')->nullable();
            $table->text('catatan_internal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PERBAIKAN: Nama tabel sekarang sudah sama persis dengan fungsi up()
        Schema::dropIfExists('laporan_limbah');
    }
};