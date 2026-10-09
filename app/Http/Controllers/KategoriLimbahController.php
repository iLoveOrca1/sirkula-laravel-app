<?php

namespace App\Http\Controllers;

use App\Models\KategoriLimbah;
use Illuminate\Http\Request;

class KategoriLimbahController extends Controller
{
    public function index()
    {
        $kategori = KategoriLimbah::all();
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_limbahs,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriLimbah::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori limbah berhasil ditambahkan.');
    }

    public function edit(KategoriLimbah $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriLimbah $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_limbahs,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori limbah berhasil diperbarui.');
    }

    public function destroy(KategoriLimbah $kategori)
    {
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori limbah berhasil dihapus.');
    }
}