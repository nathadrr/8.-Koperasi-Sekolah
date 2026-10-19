<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun khusus Admin (Dipisah)
        User::create([
            'name' => 'Administrator Koperasi',
            'email' => 'admin@koperasi.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun User Biasa / Anggota
        User::create([
            'name' => 'User Koperasi',
            'email' => 'user@koperasi.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}