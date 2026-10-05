<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            
            // Nanti di sini kita bisa tambahkan logika redirect berdasarkan Role
            // if(Auth::user()->role == 'admin') return redirect('/dashboard-admin');
            
            return redirect()->intended('/');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:mitra,pembeli' // Hanya boleh mendaftar sebagai mitra atau pembeli
        ]);

        // Enkripsi password sebelum disimpan
        $validated['password'] = bcrypt($validated['password']);

        // Buat user baru
        $user = \App\Models\User::create($validated);

        // Langsung login-kan setelah mendaftar
        Auth::login($user);

        return redirect('/')->with('success', 'Pendaftaran berhasil!');
    }
}