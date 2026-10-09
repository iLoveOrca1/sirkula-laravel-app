<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman form login
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // PERBAIKAN: Semua user yang berhasil login langsung diarahkan ke terminal /dashboard
            // Nanti file routes/web.php yang akan memilah mereka ke halaman masing-masing
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

    // Menampilkan halaman form pendaftaran
    public function register()
    {
        return view('auth.register');
    }

    // Memproses data pendaftaran
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            // Hanya izinkan pendaftaran mandiri untuk mitra dan pembeli (Admin & Petugas dibuat manual/seeder)
            'role' => 'required|in:mitra,pembeli' 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
        ]);

        // Login otomatis
        Auth::login($user);

        // PERBAIKAN: Arahkan pengguna baru ke terminal /dashboard
        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Sirkula.');
    }
}