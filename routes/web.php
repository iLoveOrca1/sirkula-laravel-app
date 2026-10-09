```php
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanLimbahController;
use App\Http\Controllers\PasarController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;

use App\Models\LaporanLimbah;

// ==========================================
// RUTE UTAMA & PUBLIK
// ==========================================

Route::get('/', function () {
    $limbahMap = LaporanLimbah::with(['mitra', 'kategori'])
        ->where('status', 'tersedia')
        ->latest()
        ->get();

    return view('home', compact('limbahMap'));
})->name('home');

// Halaman About
Route::get('/about', function () {
    return view('about');
})->name('about');

// Pasar Sirkula dan Detail Limbah
Route::get('/pasar', [PasarController::class, 'index'])
    ->name('pasar.index');

Route::get('/pasar/{id}', [PasarController::class, 'show'])
    ->whereNumber('id')
    ->name('pasar.show');


// ==========================================
// RUTE AUTENTIKASI
// ==========================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'store']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ==========================================
// RUTE KHUSUS ADMIN
// Middleware: auth dan role:admin
// ==========================================

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [DashboardController::class, 'admin'])
        ->name('dashboard.admin');

    // Verifikasi laporan oleh admin
    Route::put('/laporan/{id}/status', [
        LaporanLimbahController::class,
        'updateStatus',
    ])
        ->whereNumber('id')
        ->name('laporan.updateStatus');

    // Mengarahkan kembali jika URL status dibuka melalui GET
    Route::get('/laporan/{id}/status', function () {
        return redirect()
            ->route('dashboard.admin')
            ->with(
                'error',
                'Gunakan tombol Setujui atau Tolak pada tabel untuk memproses laporan.'
            );
    })->whereNumber('id');
});


// ==========================================
// RUTE KHUSUS MITRA
// Middleware: auth dan role:mitra
// ==========================================

Route::middleware(['auth', 'role:mitra'])->group(function () {

    // Dashboard laporan milik mitra yang login
    Route::get('/laporan-limbah', function () {
        $user = Auth::user();

        $laporan = LaporanLimbah::with('kategori')
            ->where('mitra_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard', compact('user', 'laporan'));
    })->name('laporan.index');

    // Membuat laporan
    Route::get('/laporan/buat', [LaporanLimbahController::class, 'create'])
        ->name('laporan.create');

    Route::post('/laporan/buat', [LaporanLimbahController::class, 'store'])
        ->name('laporan.store');

    // Mengedit laporan
    Route::get('/laporan/{id}/edit', [LaporanLimbahController::class, 'edit'])
        ->whereNumber('id')
        ->name('laporan.edit');

    Route::put('/laporan/{id}', [LaporanLimbahController::class, 'update'])
        ->whereNumber('id')
        ->name('laporan.update');

    // Menghapus laporan
    Route::delete('/laporan/{id}', [LaporanLimbahController::class, 'destroy'])
        ->whereNumber('id')
        ->name('laporan.destroy');
});


// ==========================================
// RUTE KHUSUS PETUGAS
// Middleware: auth dan role:petugas
// ==========================================

Route::middleware(['auth', 'role:petugas'])->group(function () {

    Route::get('/dashboard-petugas', [PetugasController::class, 'index'])
        ->name('dashboard.petugas');

    // Memperbarui status pengambilan limbah
    Route::put('/laporan/{id}/pickup', [PetugasController::class, 'updateStatus'])
        ->whereNumber('id')
        ->name('petugas.update_status');
});


// ==========================================
// RUTE PROFIL DAN PENGARAH DASHBOARD
// ==========================================

Route::middleware('auth')->group(function () {

    // Profil pengguna
    Route::get('/profil', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profil', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Pengarahan dashboard sesuai role pengguna
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'mitra') {
            return redirect()->route('laporan.index');
        }

        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        }

        if ($user->role === 'petugas') {
            return redirect()->route('dashboard.petugas');
        }

        return redirect()->route('pasar.index');
    })->name('dashboard');
});