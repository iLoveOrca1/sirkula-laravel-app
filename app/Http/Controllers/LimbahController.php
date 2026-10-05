<?php

namespace App\Http\Controllers;

use App\Models\Limbah;
use App\Models\KategoriLimbah;
use App\Models\LaporanLimbah; // <-- Tambahkan baris import ini!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LimbahController extends Controller
{
    // ... sisa kode di bawahnya tetap sama
    /**
     * Menampilkan daftar semua limbah.
     */
    public function index()
    {
        // Mengambil data limbah beserta relasi mitra dan kategori
        $limbah = Limbah::with(['mitra', 'kategori'])->latest()->get();
        return view('limbah.index', compact('limbah'));
    }

    /**
     * Menampilkan form untuk membuat data limbah baru.
     */
    public function create()
    {
        $kategori = KategoriLimbah::all();
        return view('limbah.create', compact('kategori'));
    }

    /**
     * Menyimpan data limbah baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input, pastikan tanggal_pencatatan wajib diisi
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_limbah,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'estimasi_volume' => 'required|numeric',
            'satuan' => 'required|in:kg,ton,liter,pcs',
            'tanggal_pencatatan' => 'required|date', // Wajib ada
        ]);

        // 2. Masukkan data tambahan ke dalam array
        $validated['mitra_id'] = Auth::id();
        $validated['status'] = 'menunggu';
        $validated['tanggal_pencatatan'] = $request->tanggal_pencatatan; // Pastikan ini terpanggil

        // 3. Simpan ke database
        LaporanLimbah::create($validated);

        return redirect('/dashboard')->with('success', 'Laporan limbah berhasil dikirim dan sedang menunggu verifikasi.');
    }

    /**
     * Menampilkan detail spesifik limbah.
     */
    public function show(Limbah $limbah)
    {
        return view('limbah.show', compact('limbah'));
    }

    /**
     * Menampilkan form untuk mengedit data limbah.
     */
    public function edit(Limbah $limbah)
    {
        $kategori = KategoriLimbah::all();
        return view('limbah.edit', compact('limbah', 'kategori'));
    }

    /**
     * Memperbarui data limbah di database.
     */
    public function update(Request $request, Limbah $limbah)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_limbah,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'estimasi_volume' => 'required|numeric',
            'satuan' => 'required|in:kg,ton,liter,pcs',
        ]);

        $limbah->update($validated);

        return redirect()->route('limbah.index')->with('success', 'Data limbah berhasil diperbarui.');
    }

    /**
     * Menghapus data limbah dari database.
     */
    public function destroy(Limbah $limbah)
    {
        $limbah->delete();

        return redirect()->route('limbah.index')->with('success', 'Data limbah berhasil dihapus.');
    }
}
