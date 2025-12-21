<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat beberapa barang contoh untuk testing.
     */
    public function run(): void
    {
        // Get or create kategori and lokasi to avoid hardcoded IDs
        $elektronik = Kategori::firstOrCreate(['nama_kategori' => 'Elektronik']);
        $furniture = Kategori::firstOrCreate(['nama_kategori' => 'Furniture']);
        $atk = Kategori::firstOrCreate(['nama_kategori' => 'Alat Tulis Kantor']);
        $buku = Kategori::firstOrCreate(['nama_kategori' => 'Buku Perpustakaan']);

        $labKomputer = Lokasi::firstOrCreate(['nama_lokasi' => 'Laboratorium Komputer']);
        $ruangKelas = Lokasi::firstOrCreate(['nama_lokasi' => 'Ruang Kelas 7A']);
        $ruangTu = Lokasi::firstOrCreate(['nama_lokasi' => 'Ruang Tata Usaha']);
        $ruangGuru = Lokasi::firstOrCreate(['nama_lokasi' => 'Ruang Guru']);
        $perpustakaan = Lokasi::firstOrCreate(['nama_lokasi' => 'Perpustakaan']);

        $barangs = [
            [
                'kode_barang' => 'ELK-001',
                'nama_barang' => 'Komputer Desktop Dell',
                'kategori_id' => $elektronik->id,
                'lokasi_id' => $labKomputer->id,
                'jumlah_stok' => 8,
                'reorder_point' => 5,
                'satuan' => 'unit',
                'status' => 'baik',
                'deskripsi' => 'Komputer desktop untuk lab komputer',
                'harga_satuan' => 8500000.00,
                'merk' => 'Dell',
                'tanggal_pembelian' => '2023-01-15',
            ],
            [
                'kode_barang' => 'FUR-001',
                'nama_barang' => 'Meja Belajar Siswa',
                'kategori_id' => $furniture->id,
                'lokasi_id' => $ruangKelas->id,
                'jumlah_stok' => 35,
                'reorder_point' => 30,
                'satuan' => 'unit',
                'status' => 'baik',
                'deskripsi' => 'Meja belajar untuk siswa',
                'harga_satuan' => 450000.00,
                'merk' => 'Olympic',
                'tanggal_pembelian' => '2023-06-20',
            ],
            [
                'kode_barang' => 'ATK-001',
                'nama_barang' => 'Pulpen Hitam',
                'kategori_id' => $atk->id,
                'lokasi_id' => $ruangTu->id,
                'jumlah_stok' => 5,
                'reorder_point' => 20,
                'satuan' => 'lusin',
                'status' => 'baik',
                'deskripsi' => 'Pulpen hitam untuk keperluan administrasi',
                'harga_satuan' => 35000.00,
                'merk' => 'Standard',
                'tanggal_pembelian' => '2024-11-10',
            ],
            [
                'kode_barang' => 'ELK-002',
                'nama_barang' => 'Proyektor LCD',
                'kategori_id' => $elektronik->id,
                'lokasi_id' => $ruangGuru->id,
                'jumlah_stok' => 2,
                'reorder_point' => 1,
                'satuan' => 'unit',
                'status' => 'rusak',
                'deskripsi' => 'Proyektor untuk presentasi - perlu perbaikan',
                'harga_satuan' => 5500000.00,
                'merk' => 'Epson',
                'tanggal_pembelian' => '2022-08-15',
            ],
            [
                'kode_barang' => 'BKU-001',
                'nama_barang' => 'Buku Matematika Kelas 7',
                'kategori_id' => $buku->id,
                'lokasi_id' => $perpustakaan->id,
                'jumlah_stok' => 45,
                'reorder_point' => 30,
                'satuan' => 'eksemplar',
                'status' => 'baik',
                'deskripsi' => 'Buku pelajaran matematika untuk kelas 7',
                'harga_satuan' => 85000.00,
                'merk' => 'Erlangga',
                'tanggal_pembelian' => '2024-07-01',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
