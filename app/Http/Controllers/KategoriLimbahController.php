<?php

namespace App\Http\Controllers;

use App\Models\KategoriLimbah;
use Illuminate\Http\Request;

class KategoriLimbahController extends Controller
{
    /**
     * Menampilkan daftar kategori limbah.
     */
    public function index()
    {
        $kategori = KategoriLimbah::all();
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_limbah',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriLimbah::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori limbah berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(KategoriLimbah $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, KategoriLimbah $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_limbah,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori limbah berhasil diperbarui.');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(KategoriLimbah $kategori)
    {
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori limbah berhasil dihapus.');
    }
}