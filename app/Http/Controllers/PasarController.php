<?php

namespace App\Http\Controllers;

use App\Models\Limbah;
use Illuminate\Http\Request;

class PasarController extends Controller
{
    public function index()
    {
        // Hanya ambil limbah yang statusnya 'disetujui'
        $limbahTersedia = Limbah::with(['kategori', 'mitra'])
                            ->where('status', 'disetujui')
                            ->latest()
                            ->get();

        return view('pasar.index', compact('limbahTersedia'));
    }
}