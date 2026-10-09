<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanLimbahController;
use App\Http\Controllers\PasarController;
use App\Http\Controllers\PetugasController;
use App\Models\LaporanLimbah;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// ==========================================
// RUTE UTAMA & PUBLIK (Tidak perlu Login)
// ==========================================
Route::get('/', function () {
    $limbahMap = LaporanLimbah::with(['mitra', 'kategori'])
        ->where('status', 'tersedia')
        ->latest()
        ->get();

    return view('home', compact('limbahMap'));
})->name('home');

// Pasar Sirkula dan Detail Limbah
Route::get('/pasar', [PasarController::class, 'index'])->name('pasar.index');
Route::get('/pasar/{id}', [PasarController::class, 'show'])->name('pasar.show');


// ==========================================
// RUTE AUTENTIKASI
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// RUTE KHUSUS ADMIN (Penjaga Pintu: role:admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

    // Aksi verifikasi (Setujui / Tolak) hanya lewat PUT dari form di dashboard admin
    Route::put('/laporan/{id}/status', [LaporanLimbahController::class, 'updateStatus'])
        ->whereNumber('id')
        ->name('laporan.updateStatus');

    // Jaga-jaga: jika URL aksi ini terbuka lewat GET (refresh halaman setelah submit,
    // tombol Back, atau bookmark), arahkan kembali ke dashboard, bukan error 405.
    Route::get('/laporan/{id}/status', function () {
        return redirect()
            ->route('dashboard.admin')
            ->with('error', 'Gunakan tombol Setujui atau Tolak pada tabel untuk memproses laporan.');
    })->whereNumber('id');
});


// ==========================================
// RUTE KHUSUS MITRA (Penjaga Pintu: role:mitra)
// ==========================================
Route::middleware(['auth', 'role:mitra'])->group(function () {
    Route::get('/laporan-limbah', function () {
        $user = Auth::user();
        $laporan = LaporanLimbah::with('kategori')
            ->where('mitra_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard', compact('user', 'laporan'));
    })->name('laporan.index');

    Route::get('/laporan/buat', [LaporanLimbahController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/buat', [LaporanLimbahController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{id}/edit', [LaporanLimbahController::class, 'edit'])->whereNumber('id')->name('laporan.edit');
    Route::put('/laporan/{id}', [LaporanLimbahController::class, 'update'])->whereNumber('id')->name('laporan.update');
    Route::delete('/laporan/{id}', [LaporanLimbahController::class, 'destroy'])->whereNumber('id')->name('laporan.destroy');
});


// ==========================================
// RUTE KHUSUS PETUGAS (Penjaga Pintu: role:petugas)
// ==========================================
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard-petugas', [PetugasController::class, 'index'])->name('dashboard.petugas');
    Route::put('/laporan/{id}/pickup', [PetugasController::class, 'updateStatus'])
        ->whereNumber('id')
        ->name('petugas.update_status');
});


// ==========================================
// RUTE PENGARAH DASBOR & PROFIL
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'mitra') {
            return redirect()->route('laporan.index');
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role === 'petugas') {
            return redirect()->route('dashboard.petugas');
        } else {
            return redirect()->route('pasar.index');
        }
    })->name('dashboard');
});