<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\TransaksiBarang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransaksiBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all barang and users
        $barangs = Barang::all();
        $users = User::all();

        if ($barangs->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Pastikan Barang dan User sudah di-seed terlebih dahulu!');

            return;
        }

        $penanggungJawabNames = [
            'Budi Santoso',
            'Siti Nurhaliza',
            'Ahmad Rahman',
            'Dewi Kusuma',
            'Roni Wijaya',
            'Maria Santoso',
            'Eka Putri',
            'Fajar Maulana',
        ];

        $keterangan = [
            'Pengadaan rutin',
            'Pembelian tambahan',
            'Penerimaan dari supplier',
            'Pengiriman ke cabang',
            'Penyimpanan di gudang',
            'Distribusi ke toko',
            'Inventaris bulanan',
            'Koreksi stok',
        ];

        $tanggalKode = [];
        // Track current stock levels for each barang during seeding
        $stockTracking = [];
        foreach ($barangs as $barang) {
            $stockTracking[$barang->id] = $barang->jumlah_stok;
        }

        // Generate transactions for December 1-30, 2025
        for ($day = 1; $day <= 30; $day++) {
            $tanggal = Carbon::create(2025, 12, $day);
            $tanggalStr = $tanggal->format('Ymd');
            $tanggalKode[$tanggalStr] = ['masuk' => 0, 'keluar' => 0];

            // Random number of transactions per day (1-5)
            $jumlahTransaksiHari = rand(1, 5);

            for ($i = 0; $i < $jumlahTransaksiHari; $i++) {
                $barang = $barangs->random();
                $currentStock = $stockTracking[$barang->id];

                // Decide transaction type: if stock is low, do masuk; otherwise random
                if ($currentStock <= 5) {
                    $tipeTransaksi = 'masuk';
                } else {
                    $tipeTransaksi = rand(0, 1) ? 'masuk' : 'keluar';
                }

                // Calculate safe quantity
                if ($tipeTransaksi === 'keluar') {
                    // Only create keluar if there's stock, and limit to current stock
                    if ($currentStock <= 0) {
                        continue; // Skip this transaction
                    }
                    $maxJumlah = min(20, $currentStock);
                    $jumlah = rand(1, max(1, $maxJumlah));
                    $stockTracking[$barang->id] -= $jumlah;
                    $approvalStatus = 'approved'; // Outgoing doesn't need approval
                } else {
                    // masuk transaction - set to approved so stock is updated during seeding
                    $jumlah = rand(5, 50);
                    $stockTracking[$barang->id] += $jumlah;
                    $approvalStatus = 'approved'; // Approve during seeding
                }

                $user = $users->random();

                // Generate unique kode_transaksi
                $prefix = $tipeTransaksi === 'masuk' ? 'TM' : 'TK';
                $tanggalKode[$tanggalStr][$tipeTransaksi]++;
                $sequence = $tanggalKode[$tanggalStr][$tipeTransaksi];
                $kodeTransaksi = sprintf('%s-%s-%03d', $prefix, $tanggalStr, $sequence);

                TransaksiBarang::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'barang_id' => $barang->id,
                    'tipe_transaksi' => $tipeTransaksi,
                    'jumlah' => $jumlah,
                    'tanggal_transaksi' => $tanggal,
                    'penanggung_jawab' => rand(0, 1) ? collect($penanggungJawabNames)->random() : null,
                    'keterangan' => rand(0, 1) ? collect($keterangan)->random() : null,
                    'user_id' => $user->id,
                    'approval_status' => $approvalStatus,
                    'approved_by' => $approvalStatus === 'approved' ? $user->id : null,
                    'approved_at' => $approvalStatus === 'approved' ? now() : null,
                ]);
            }

            $this->command->info("✓ Transaksi untuk {$tanggal->format('d F Y')} berhasil dibuat ({$jumlahTransaksiHari} transaksi)");
        }

        $this->command->info('✓ Seeding TransaksiBarang selesai!');
    }
}
