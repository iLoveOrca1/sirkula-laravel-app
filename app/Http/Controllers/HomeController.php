<?php

namespace App\Http\Controllers;

use App\Models\Limbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil laporan khusus untuk mitra yang sedang login, diurutkan dari yang terbaru.
        // Eager loading ('kategori') digunakan agar query lebih cepat dan efisien.
        $laporan = Limbah::with('kategori')
                    ->where('mitra_id', Auth::id())
                    ->latest()
                    ->get();

        return view('dashboard', compact('laporan')); 
        // Catatan: sesuaikan 'dashboard' dengan nama file view Anda (misal: 'home' atau 'dashboard.index')
    }
}