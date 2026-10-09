<?php

namespace App\Http\Controllers;

use App\Models\LaporanLimbah;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil seluruh laporan limbah yang memiliki koordinat latitude & longitude
        // Beserta relasi kategori dan mitra agar dapat ditampilkan di peta dan card
        $laporan = LaporanLimbah::with(['kategori', 'mitra'])
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->latest()
                    ->get();

        return view('dashboard', compact('laporan'));
    }
}