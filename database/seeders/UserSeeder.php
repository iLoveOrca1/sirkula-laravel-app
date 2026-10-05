<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Mitra Hotel Sagara',
            'email' => 'mitra@sirkula.com',
            'password' => Hash::make('password123'),
            'role' => 'mitra',
            'nama_instansi' => 'Hotel Sagara Resort',
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Petugas Sirkula',
            'email' => 'petugas@sirkula.com',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);
    }
}