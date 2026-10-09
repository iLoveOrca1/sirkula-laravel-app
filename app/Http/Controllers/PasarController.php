<?php

namespace App\Http\Controllers;

use App\Models\LaporanLimbah;
use Illuminate\Http\Request;

class PasarController extends Controller
{
    /**
     * Menampilkan daftar limbah yang tersedia di pasar/jelajah limbah.
     */
    public function index()
    {
        // Mengambil data limbah yang berstatus 'Tersedia' atau 'tersedia' dari database
        $limbahTersedia = LaporanLimbah::with(['kategori', 'mitra'])
            ->whereRaw('LOWER(status) = ?', ['tersedia'])
            ->latest()
            ->paginate(9);

        return view('pasar.index', compact('limbahTersedia'));
    }

    /**
     * Menampilkan detail informasi limbah tertentu.
     */
    public function show($id)
    {
        // Cari data limbah berdasarkan ID beserta relasi kategori dan mitranya
        $limbah = LaporanLimbah::with(['kategori', 'mitra'])->findOrFail($id);

        return view('pasar.show', compact('limbah'));
    }
}