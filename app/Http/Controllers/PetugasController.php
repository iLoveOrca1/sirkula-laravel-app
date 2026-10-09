<?php

namespace App\Http\Controllers;

use App\Models\LaporanLimbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    // Menampilkan halaman Dasbor Petugas
    public function index()
    {
        // Petugas hanya melihat limbah yang berstatus 'tersedia', 'sedang dijemput', atau 'selesai'
        // (Status 'menunggu' dan 'ditolak' adalah urusan Admin)
        // Semua status disimpan huruf kecil agar sesuai dengan check constraint database.
        $laporan = LaporanLimbah::with(['kategori', 'mitra'])
            ->whereIn('status', ['tersedia', 'sedang dijemput', 'selesai'])
            ->latest()
            ->get();

        $user = Auth::user();

        return view('petugas.dashboard', compact('laporan', 'user'));
    }

    // Mengubah status penjemputan
    public function updateStatus(Request $request, $id)
    {
        $laporan = LaporanLimbah::findOrFail($id);

        // Validasi aksi khusus petugas (terima huruf besar/kecil, simpan huruf kecil)
        $request->merge(['status' => strtolower((string) $request->status)]);

        $request->validate([
            'status' => 'required|in:sedang dijemput,selesai'
        ]);

        // Laporan yang belum disetujui / sudah ditolak tidak boleh dijemput
        if (!in_array(strtolower((string) $laporan->status), ['tersedia', 'sedang dijemput', 'selesai'])) {
            return redirect()->back()->with('error', 'Laporan ini belum disetujui Admin, jadi belum bisa diproses.');
        }

        $laporan->status = $request->status;
        $laporan->save();

        $pesan = $request->status === 'sedang dijemput'
            ? 'Status diubah! Segera meluncur ke lokasi Mitra.'
            : 'Mantap! Tugas penjemputan limbah selesai.';

        return redirect()->back()->with('success', $pesan);
    }
}