<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat 3 pengguna dengan role berbeda untuk testing.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventaris.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Petugas Inventaris user
        User::create([
            'name' => 'Petugas Inventaris',
            'email' => 'petugas@inventaris.test',
            'password' => Hash::make('password'),
            'role' => 'petugas_inventaris',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Kepala Sekolah user
        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepala@inventaris.test',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
