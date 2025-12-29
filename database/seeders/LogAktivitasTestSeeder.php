<?php

namespace Database\Seeders;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Database\Seeder;

class LogAktivitasTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Menggunakan factory untuk generate data log aktivitas.
     */
    public function run(): void
    {
        // Pastikan user sudah ada
        if (User::count() === 0) {
            User::factory(3)->create();
        }

        // Create logs untuk berbagai jenis aktivitas
        LogAktivitas::factory(20)->login()->create();
        LogAktivitas::factory(15)->createActivity()->create();
        LogAktivitas::factory(15)->updateActivity()->create();
        LogAktivitas::factory(10)->deleteActivity()->create();
    }
}
