<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Jalankan seeder dalam urutan yang benar sesuai dengan foreign key dependencies.
     * Urutan penting untuk menghindari FK constraint errors.
     */
    public function run(): void
    {
        // 1. Setup permissions dan roles
        $this->call(RolePermissionSeeder::class);

        // 2. Setup users dengan roles
        $this->call(UserSeeder::class);

        // 3. Setup master data (kategori dan lokasi) - tidak ada FK dependencies
        $this->call(KategoriLokasiSeeder::class);

        // 4. Setup barang - FK ke kategori dan lokasi
        $this->call(BarangTestSeeder::class);

        // 5. Setup transaksi - FK ke barang dan user
        $this->call(TransaksiBarangTestSeeder::class);

        // 6. Setup log aktivitas - FK ke user
        $this->call(LogAktivitasTestSeeder::class);
    }
}
