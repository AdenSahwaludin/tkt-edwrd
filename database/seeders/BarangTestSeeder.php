<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class BarangTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Menggunakan factory untuk generate data barang yang konsisten.
     */
    public function run(): void
    {
        // Pastikan kategori dan lokasi sudah ada
        if (Kategori::count() === 0) {
            Kategori::factory(7)->create();
        }

        if (Lokasi::count() === 0) {
            Lokasi::factory(5)->create();
        }

        // Create 50 barang untuk testing
        Barang::factory(50)->create();
    }
}
