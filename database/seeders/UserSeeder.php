<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat pengguna dengan role berbeda untuk testing.
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

        // Petugas Inventaris user with Petugas Inventaris role
        $petugas = User::create([
            'name' => 'Petugas Inventaris',
            'email' => 'petugas@inventaris.test',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $petugas->assignRole('Petugas Inventaris');

        // Staff user with Staff role
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@inventaris.test',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $staff->assignRole('Staff');
    }
}
