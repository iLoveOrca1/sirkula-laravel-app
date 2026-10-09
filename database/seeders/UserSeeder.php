<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Utama (Untuk Dasbor Admin)
        User::create([
            'name' => 'Admin Jinwoo',
            'email' => 'admin@sirkula.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Petugas
        User::create([
            'name' => 'Petugas Sirkula',
            'email' => 'petugas@sirkula.com',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        // 3. Akun Mitra
        User::create([
            'name' => 'Mitra Hotel Sagara',
            'email' => 'mitra@sirkula.com',
            'password' => Hash::make('password123'),
            'role' => 'mitra',
            'nama_instansi' => 'Hotel Sagara Resort',
            'is_verified' => true,
        ]);
    }
}