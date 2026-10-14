<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Menampilkan halaman form login & register terpadu
    public function index()
    {
        return view('auth.login');
    }

    // Memproses data form login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Checkbox "Ingat perangkat ini" pada form
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    // Memproses log out
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Menampilkan halaman form pendaftaran (jika diakses via route terpisah)
    public function register()
    {
        return view('auth.login');
    }

    // Memproses data pendaftaran mitra baru dari form terpadu
    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'], // Nama Bisnis / Fasilitas
            'jenis_usaha'    => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'whatsapp'       => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'alamat_lengkap' => ['required', 'string', 'max:500'], // Kota / Lokasi Fasilitas
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique'   => 'Email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Contoh: +62 812-3456-7890.',
            'password.min'   => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        // "Kecamatan, Kabupaten/Kota" -> bagian pertama dipakai sebagai wilayah (kelurahan)
        // agar muncul di filter wilayah dan peta beranda.
        $lokasi    = trim($request->alamat_lengkap);
        $wilayah   = trim(Str::before($lokasi, ','));

        $user = User::create([
            'name'           => trim($request->name),
            'nama_instansi'  => trim($request->name), // Nama bisnis juga disimpan sebagai nama instansi
            'jenis_usaha'    => $request->jenis_usaha,
            'email'          => $request->email,
            'no_whatsapp'    => trim($request->whatsapp),
            'alamat_lengkap' => $lokasi,
            'kelurahan'      => $wilayah !== '' ? $wilayah : null,
            // Role SELALU mitra. Tidak diambil dari input form agar tidak bisa dimanipulasi.
            'role'           => 'mitra',
            'password'       => Hash::make($request->password),
        ]);

        // Login otomatis setelah registrasi berhasil
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Sirkula.');
    }
}