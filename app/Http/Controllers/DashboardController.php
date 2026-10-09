<?php

namespace App\Http\Controllers;

use App\Models\LaporanLimbah; // <-- Ubah dari Limbah menjadi LaporanLimbah
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        // Ambil semua laporan beserta relasi kategori dan mitra
        $semuaLaporan = LaporanLimbah::with(['kategori', 'mitra'])->latest()->get();
        
        $user = Auth::user();

        return view('admin.dashboard-admin', compact('semuaLaporan', 'user'));
    }
}