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
        // Admin user with Admin Sistem role
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventaris.test',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Admin Sistem');

        // Petugas Inventaris user with Staff role
        $petugas = User::create([
            'name' => 'Petugas Inventaris',
            'email' => 'petugas@inventaris.test',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $petugas->assignRole('Staff');

        // Kepala Sekolah user with Staff role
        $kepala = User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepala@inventaris.test',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $kepala->assignRole('Staff');
    }
}
