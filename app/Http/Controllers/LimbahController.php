<?php

namespace App\Http\Controllers;

use App\Models\KategoriLimbah;
use App\Models\LaporanLimbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LimbahController extends Controller
{
    /**
     * Menampilkan daftar semua limbah.
     */
    public function index()
    {
        // Mengambil data laporan limbah beserta relasi mitra dan kategori
        $limbah = LaporanLimbah::with(['mitra', 'kategori'])->latest()->get();
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
        // 1. Validasi input, perbaiki referensi tabel menjadi kategori_limbahs
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_limbahs,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'estimasi_volume' => 'required|numeric',
            'satuan' => 'required|in:kg,ton,liter,pcs',
            'tanggal_pencatatan' => 'required|date',
        ]);

        // 2. Masukkan data tambahan ke dalam array
        $validated['mitra_id'] = Auth::id();
        $validated['status'] = 'menunggu';
        $validated['tanggal_pencatatan'] = $request->tanggal_pencatatan;

        // 3. Simpan ke database
        LaporanLimbah::create($validated);

        return redirect('/dashboard')->with('success', 'Laporan limbah berhasil dikirim dan sedang menunggu verifikasi.');
    }

    /**
     * Menampilkan detail spesifik limbah.
     */
    public function show(LaporanLimbah $limbah)
    {
        return view('limbah.show', compact('limbah'));
    }

    /**
     * Menampilkan form untuk mengedit data limbah.
     */
    public function edit(LaporanLimbah $limbah)
    {
        $kategori = KategoriLimbah::all();
        return view('limbah.edit', compact('limbah', 'kategori'));
    }

    /**
     * Memperbarui data limbah di database.
     */
    public function update(Request $request, LaporanLimbah $limbah)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_limbahs,id',
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
    public function destroy(LaporanLimbah $limbah)
    {
        $limbah->delete();

        return redirect()->route('limbah.index')->with('success', 'Data limbah berhasil dihapus.');
    }
}