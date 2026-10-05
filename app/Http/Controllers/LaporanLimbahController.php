<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriLimbah;
use App\Models\Limbah; // <-- PERBAIKAN: Menggunakan model Limbah sesuai file sebelumnya
use Illuminate\Support\Facades\Auth;

class LaporanLimbahController extends Controller
{
    // Menampilkan halaman form
    public function create()
    {
        if (Auth::user()->role !== 'mitra') {
            abort(403, 'Akses Ditolak. Hanya Mitra yang dapat membuat laporan limbah.');
        }

        $kategori = KategoriLimbah::all();

        return view('laporan.create', compact('kategori'));
    }

    // Memproses penyimpanan data
    public function store(Request $request)
    {
        // 1. PERBAIKAN: Sesuaikan validasi dengan nama kolom di database baru
        $validated = $request->validate([
            'kategori_id'        => 'required|exists:kategori_limbah,id',
            'tanggal_pencatatan' => 'required|date',
            'volume_dihasilkan'  => 'required|numeric',
            'satuan'             => 'required|in:kg,ton,liter,pcs', // Sesuaikan pilihan satuan Anda
            'catatan_internal'   => 'nullable|string',
            'pihak_pengambil'    => 'nullable|string',
        ]);

        // 2. Tambahkan ID user yang sedang login
        $validated['mitra_id'] = Auth::id();

        // CATATAN PENTING: $validated['status'] = 'menunggu'; SAYA HAPUS karena
        // di file migrasi Anda sebelumnya TIDAK ADA kolom 'status'. 
        // Jika dibiarkan, akan muncul error "column status does not exist".

        // 3. Simpan data ke database
        Limbah::create($validated);

        return redirect('/dashboard')->with('success', 'Laporan limbah berhasil dikirim.');
    }

    // Menampilkan halaman form edit
    public function edit(Limbah $limbah)
    {
        // Pastikan hanya pemilik laporan yang bisa mengedit
        if ($limbah->mitra_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        $kategori = KategoriLimbah::all();
        return view('laporan.edit', compact('limbah', 'kategori'));
    }

    // Memproses update data ke database
    public function update(Request $request, Limbah $limbah)
    {
        if ($limbah->mitra_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'kategori_id'        => 'required|exists:kategori_limbah,id',
            'tanggal_pencatatan' => 'required|date',
            'volume_dihasilkan'  => 'required|numeric',
            'satuan'             => 'required|in:kg,ton,liter,pcs',
            'catatan_internal'   => 'nullable|string',
        ]);

        $limbah->update($validated);

        return redirect('/dashboard')->with('success', 'Laporan berhasil diperbarui.');
    }

    // Memproses hapus data dari database
    public function destroy(Limbah $limbah)
    {
        if ($limbah->mitra_id !== Auth::id()) {
            abort(403);
        }

        $limbah->delete();

        return redirect('/dashboard')->with('success', 'Laporan berhasil dihapus.');
    }

    // Memproses perubahan status oleh Admin
    public function updateStatus(Request $request, Limbah $limbah)
    {
        // Validasi agar datanya pasti antara disetujui atau ditolak
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        // Update kolom status di database
        $limbah->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui menjadi: ' . ucfirst($request->status));
    }
}
