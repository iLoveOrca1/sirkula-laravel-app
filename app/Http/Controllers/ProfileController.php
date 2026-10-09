<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    // Menampilkan halaman form edit profil
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Memproses pembaruan data
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi input dari user termasuk field lokasi baru
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nama_instansi' => 'nullable|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string',
            'kelurahan' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 2. Siapkan data yang akan diperbarui
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_whatsapp' => $request->no_whatsapp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kelurahan' => $request->kelurahan,
            'kode_pos' => $request->kode_pos,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($user->role === 'mitra') {
            $data['nama_instansi'] = $request->nama_instansi;
        }

        // 3. Tambahkan password baru jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 4. Simpan ke database menggunakan method update()
        $user->update($data);

        return redirect()->route('profile.edit')->with('success', 'Profil dan titik lokasi berhasil diperbarui!');
    }
}