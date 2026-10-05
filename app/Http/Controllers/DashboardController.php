<?php

namespace App\Http\Controllers;

use App\Models\Limbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        // Ambil semua laporan beserta relasi kategori dan siapa pembuatnya (mitra)
        $semuaLaporan = Limbah::with(['kategori', 'mitra'])->latest()->get();
        
        $user = Auth::user();

        return view('admin.dashboard-admin', compact('semuaLaporan', 'user'));
    }
}