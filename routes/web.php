<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanLimbahController;
use App\Models\Limbah;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PasarController;

Route::get('/', function () {
    return redirect('/login');
})->name('home');
// Rute Autentikasi (Bisa diakses tanpa login)
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store']);


// ==========================================
// RUTE KHUSUS ADMIN
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {

    // Halaman Dashboard Admin
    Route::get('/dashboard-admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

    // Proses Ubah Status Laporan (Setujui/Tolak)
    Route::put('/laporan/{limbah}/status', [LaporanLimbahController::class, 'updateStatus'])->name('laporan.update_status');
});


// ==========================================
// RUTE UMUM (HANYA BISA DIAKSES JIKA SUDAH LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Rute Dashboard Utama (Untuk Mitra / Pembeli)
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $laporan = []; // Siapkan kotak kosong agar tidak error jika yang login bukan mitra

        // Jika yang login adalah mitra, ambil riwayat laporan miliknya dari database
        if ($user->role === 'mitra') {
            $laporan = Limbah::with('kategori')
                ->where('mitra_id', $user->id)
                ->latest()
                ->get();
        }

        // Kirim data $user dan $laporan ke tampilan dashboard.blade.php
        return view('dashboard', compact('user', 'laporan'));
    })->name('dashboard');


    // Rute Pelaporan Limbah (CRUD)
    Route::get('/laporan/buat', [LaporanLimbahController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/buat', [LaporanLimbahController::class, 'store'])->name('laporan.store');

    // Rute Edit dan Hapus Laporan
    Route::get('/laporan/{limbah}/edit', [LaporanLimbahController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{limbah}', [LaporanLimbahController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan/{limbah}', [LaporanLimbahController::class, 'destroy'])->name('laporan.destroy');

    // Rute Pasar Sirkula
    Route::get('/pasar', [PasarController::class, 'index'])->name('pasar.index');
});
