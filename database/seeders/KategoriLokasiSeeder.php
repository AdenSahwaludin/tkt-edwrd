<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class KategoriLokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat data master kategori dan lokasi untuk testing.
     */
    public function run(): void
    {
        // Kategori
        $kategoris = [
            [
                'nama_kategori' => 'Elektronik',
                'deskripsi' => 'Peralatan elektronik seperti komputer, printer, proyektor',
            ],
            [
                'nama_kategori' => 'Furniture',
                'deskripsi' => 'Meja, kursi, lemari, rak buku',
            ],
            [
                'nama_kategori' => 'Alat Tulis Kantor',
                'deskripsi' => 'Kertas, pulpen, spidol, penghapus, tinta printer',
            ],
            [
                'nama_kategori' => 'Alat Kebersihan',
                'deskripsi' => 'Sapu, pel, pembersih lantai, lap pel',
            ],
            [
                'nama_kategori' => 'Peralatan Olahraga',
                'deskripsi' => 'Bola, net, matras, badminton',
            ],
            [
                'nama_kategori' => 'Buku Perpustakaan',
                'deskripsi' => 'Buku pelajaran, buku referensi, novel',
            ],
            [
                'nama_kategori' => 'Alat Laboratorium',
                'deskripsi' => 'Peralatan praktikum sains dan teknologi',
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(['nama_kategori' => $kategori['nama_kategori']], $kategori);
        }

        // Lokasi
        $lokasis = [
            [
                'nama_lokasi' => 'Gudang Penyimpanan',
                'gedung' => 'Gedung Belakang',
                'lantai' => 1,
                'keterangan' => 'Gudang utama untuk penyimpanan barang inventaris',
            ],
            [
                'nama_lokasi' => 'Ruang Kepala Sekolah',
                'gedung' => 'Gedung Utama',
                'lantai' => 2,
                'keterangan' => 'Ruang kerja kepala sekolah',
            ],
            [
                'nama_lokasi' => 'Laboratorium Komputer',
                'gedung' => 'Gedung Laboratorium',
                'lantai' => 2,
                'keterangan' => 'Lab komputer untuk praktikum siswa',
            ],
            [
                'nama_lokasi' => 'Perpustakaan',
                'gedung' => 'Gedung Utama',
                'lantai' => 2,
                'keterangan' => 'Ruang perpustakaan sekolah',
            ],
            [
                'nama_lokasi' => 'Ruang Kelas',
                'gedung' => 'Gedung Kelas',
                'lantai' => 1,
                'keterangan' => 'Ruang belajar mengajar siswa',
            ],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::firstOrCreate(['nama_lokasi' => $lokasi['nama_lokasi']], $lokasi);
        }
    }
}
