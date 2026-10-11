<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom status sebelumnya enum:
     *   menunggu, tersedia, ditolak, sedang dijemput, selesai
     * sementara aplikasi memakai juga "sedang diproses" dan "sudah diambil".
     * Nilai di luar enum akan ditolak database. Kolom diubah menjadi string
     * agar semua status yang dipakai aplikasi bisa tersimpan.
     */
    public function up(): void
    {
        // Di PostgreSQL, enum Laravel = varchar + CHECK constraint; constraint harus dibuang manual.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE laporan_limbahs DROP CONSTRAINT IF EXISTS laporan_limbahs_status_check');
        }

        Schema::table('laporan_limbahs', function (Blueprint $table) {
            $table->string('status', 50)->default('menunggu')->change();
        });
    }

    public function down(): void
    {
        // Sengaja kosong: mengembalikan enum akan gagal jika sudah ada data dengan status baru.
    }
};