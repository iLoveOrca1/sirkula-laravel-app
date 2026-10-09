<?php

namespace App\Http\Controllers;

use App\Models\LaporanLimbah;
use App\Models\KategoriLimbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanLimbahController extends Controller
{
    // 1. Menampilkan form Tambah Limbah
    public function create()
    {
        // Ambil data kategori untuk ditampilkan di dropdown form
        $kategori = KategoriLimbah::all();
        return view('laporan.create', compact('kategori'));
    }

    // 2. Memproses data dari form, mengunggah foto, dan menyimpan ke database
    public function store(Request $request)
    {
        // A. Validasi Data, Foto, dan Pilihan Opsi Lokasi
        $request->validate([
            'judul'             => 'required|string|max:255',
            'kategori_id'       => 'required|exists:kategori_limbahs,id',
            'estimasi_volume'   => 'required|numeric',
            'satuan'            => 'required|string',
            'deskripsi'         => 'required|string',
            'foto_limbah'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_lokasi'      => 'required|in:utama,lainnya',
            'latitude'          => 'required_if:jenis_lokasi,lainnya|nullable|numeric',
            'longitude'         => 'required_if:jenis_lokasi,lainnya|nullable|numeric',
        ]);

        // B. Proses Unggah Foto
        $fotoPath = null;
        if ($request->hasFile('foto_limbah')) {
            $fotoPath = $request->file('foto_limbah')->store('foto_limbah', 'public');
        }

        $user = Auth::user();

        // C. Tentukan Koordinat Berdasarkan Pilihan Mitra
        if ($request->jenis_lokasi === 'utama') {
            $latitude = $user->latitude ?? -8.6705;
            $longitude = $user->longitude ?? 115.2126;
        } else {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        }

        // D. Simpan Semua Data ke Database
        LaporanLimbah::create([
            'mitra_id'           => $user->id,
            'kategori_id'        => $request->kategori_id,
            'judul'              => $request->judul,
            'deskripsi'          => $request->deskripsi,
            'estimasi_volume'    => $request->estimasi_volume,
            'satuan'             => strtolower($request->satuan),
            'tanggal_pencatatan' => now(),
            'status'             => 'menunggu',
            'foto_limbah'        => $fotoPath,
            'latitude'           => $latitude,
            'longitude'          => $longitude,
        ]);

        return redirect()->route('dashboard')->with('success', 'Limbah berhasil dipublikasikan!');
    }

    // 3. Menampilkan Form Edit Data
    public function edit($id)
    {
        // Cari data limbah berdasarkan ID
        $laporan = LaporanLimbah::findOrFail($id);

        // Keamanan tambahan: Pastikan Mitra hanya bisa mengedit limbahnya sendiri
        if ($laporan->mitra_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        // Ambil data kategori untuk dropdown
        $kategori = KategoriLimbah::all();

        return view('laporan.edit', compact('laporan', 'kategori'));
    }

    // 4. Memproses Perubahan Data (Update)
    public function update(Request $request, $id)
    {
        $laporan = LaporanLimbah::findOrFail($id);

        if ($laporan->mitra_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'judul'             => 'required|string|max:255',
            'kategori_id'       => 'required|exists:kategori_limbahs,id',
            'estimasi_volume'   => 'required|numeric',
            'satuan'            => 'required|string',
            'deskripsi'         => 'required|string',
            'foto_limbah'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_lokasi'      => 'required|in:utama,lainnya',
            'latitude'          => 'required_if:jenis_lokasi,lainnya|nullable|numeric',
            'longitude'         => 'required_if:jenis_lokasi,lainnya|nullable|numeric',
        ]);

        $user = Auth::user();

        // Tentukan Koordinat Berdasarkan Pilihan Mitra
        if ($request->jenis_lokasi === 'utama') {
            $laporan->latitude = $user->latitude ?? -8.6705;
            $laporan->longitude = $user->longitude ?? 115.2126;
        } else {
            $laporan->latitude = $request->latitude;
            $laporan->longitude = $request->longitude;
        }

        $laporan->judul = $request->judul;
        $laporan->kategori_id = $request->kategori_id;
        $laporan->estimasi_volume = $request->estimasi_volume;
        $laporan->satuan = strtolower($request->satuan);
        $laporan->deskripsi = $request->deskripsi;

        // Proses Jika Ada Foto Baru yang Diunggah
        if ($request->hasFile('foto_limbah')) {
            if ($laporan->foto_limbah && Storage::disk('public')->exists($laporan->foto_limbah)) {
                Storage::disk('public')->delete($laporan->foto_limbah);
            }
            $laporan->foto_limbah = $request->file('foto_limbah')->store('foto_limbah', 'public');
        }

        $laporan->save();

        return redirect()->route('dashboard')->with('success', 'Informasi limbah berhasil diperbarui!');
    }

    // 5. Menghapus Data Limbah (Delete)
    public function destroy($id)
    {
        $laporan = LaporanLimbah::findOrFail($id);

        if ($laporan->mitra_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        if ($laporan->foto_limbah && Storage::disk('public')->exists($laporan->foto_limbah)) {
            Storage::disk('public')->delete($laporan->foto_limbah);
        }

        $laporan->delete();

        return redirect()->route('dashboard')->with('success', 'Data limbah berhasil dihapus permanen!');
    }

    // 6. Mengubah Status Laporan (Khusus Admin)
    public function updateStatus(Request $request, $id)
    {
        $laporan = LaporanLimbah::findOrFail($id);

        // Samakan ke huruf kecil dulu, lalu validasi
        $request->merge(['status' => strtolower((string) $request->status)]);

        $request->validate([
            'status' => 'required|in:tersedia,ditolak'
        ]);

        // Admin hanya memverifikasi laporan yang masih menunggu
        if (strtolower((string) $laporan->status) !== 'menunggu') {
            return redirect()->route('dashboard.admin')->with('error', 'Laporan ini sudah diproses sebelumnya.');
        }

        // Status disimpan huruf kecil agar sesuai dengan check constraint database
        $laporan->status = $request->status;
        $laporan->save();

        $pesan = $request->status === 'tersedia'
            ? 'Limbah disetujui dan diterbitkan ke Pasar!'
            : 'Laporan limbah ditolak.';

        return redirect()->route('dashboard.admin')->with('success', $pesan);
    }
}