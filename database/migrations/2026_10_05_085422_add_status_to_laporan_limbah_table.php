<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_limbah', function (Blueprint $table) {
            // Menambahkan kolom status dengan 3 pilihan, default-nya adalah 'menunggu'
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('catatan_internal');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_limbah', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};