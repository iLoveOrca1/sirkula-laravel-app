<?php

namespace App\Http\Controllers;

use App\Models\PermintaanMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanMaterialController extends Controller
{
    // Menampilkan halaman form
    public function create()
    {
        return view('permintaan');
    }

    // Menyimpan data dari form
    public function store(Request $request)
    {
        $request->validate([
            'nama_material' => 'required|string|max:255',
            'kategori'      => 'required|string',
            'volume'        => 'required|numeric',
            'satuan'        => 'required|string',
            'frekuensi'     => 'required|string',
            'lokasi'        => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'whatsapp'      => 'required|string|max:20',
        ]);

        PermintaanMaterial::create([
            'user_id'       => Auth::id(),
            'nama_material' => $request->nama_material,
            'kategori'      => $request->kategori,
            'volume'        => $request->volume,
            'satuan'        => $request->satuan,
            'frekuensi'     => $request->frekuensi,
            'lokasi'        => $request->lokasi,
            'deskripsi'     => $request->deskripsi,
            'whatsapp'      => $request->whatsapp,
        ]);

        return redirect()->route('dashboard.permintaan')->with('success', 'Permintaan material berhasil dipublikasikan!');
    }

    // Menampilkan halaman Dashboard Permintaan (untuk Mitra) dengan Filter
    public function dashboard(Request $request)
    {
        $query = PermintaanMaterial::with('user')->latest();

        // Fitur Search
        if ($request->filled('search')) {
            $query->where('nama_material', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Eksekusi Query dengan Pagination (6 data per halaman)
        $permintaans = $query->paginate(6)->withQueryString();

        return view('permintaan.dashboard', compact('permintaans'));
    }
}