<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\TransaksiBarang;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransaksiBarangTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat transaksi masuk dulu untuk build stok barang,
     * baru transaksi keluar agar tidak error validasi stok.
     */
    public function run(): void
    {
        // Pastikan barang dan user sudah ada
        if (Barang::count() === 0) {
            Barang::factory(20)->create();
        }

        if (User::count() === 0) {
            User::factory(3)->create();
        }

        $users = User::all();

        // Create transaksi masuk dulu untuk build stok
        foreach (Barang::all()->take(15) as $barang) {
            TransaksiBarang::create([
                'kode_transaksi' => 'TRX-IN-'.str_pad($barang->id, 6, '0', STR_PAD_LEFT),
                'barang_id' => $barang->id,
                'tipe_transaksi' => 'masuk',
                'jumlah' => 100,
                'tanggal_transaksi' => now()->subMonths(3)->toDateString(),
                'penanggung_jawab' => 'Supplier Utama',
                'keterangan' => 'Pengadaan barang dari supplier',
                'user_id' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => now()->subMonths(3),
                'approval_status' => 'approved',
            ]);
        }

        // Create transaksi keluar setelah stok terpenuhi
        foreach (Barang::all()->take(10) as $barang) {
            TransaksiBarang::create([
                'kode_transaksi' => 'TRX-OUT-'.str_pad($barang->id, 6, '0', STR_PAD_LEFT),
                'barang_id' => $barang->id,
                'tipe_transaksi' => 'keluar',
                'jumlah' => 15,
                'tanggal_transaksi' => now()->subMonths(2)->toDateString(),
                'penanggung_jawab' => 'Petugas Inventaris',
                'keterangan' => 'Pengeluaran barang untuk kegiatan',
                'user_id' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => now()->subMonths(2),
                'approval_status' => 'approved',
            ]);
        }

        // Create transaksi pending
        foreach (Barang::all()->take(5) as $barang) {
            TransaksiBarang::create([
                'kode_transaksi' => 'TRX-PND-'.str_pad($barang->id, 6, '0', STR_PAD_LEFT),
                'barang_id' => $barang->id,
                'tipe_transaksi' => 'masuk',
                'jumlah' => 50,
                'tanggal_transaksi' => now()->toDateString(),
                'penanggung_jawab' => 'Supplier',
                'keterangan' => 'Menunggu approval',
                'user_id' => $users->random()->id,
                'approval_status' => 'pending',
            ]);
        }
    }
}
