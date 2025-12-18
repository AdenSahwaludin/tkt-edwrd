<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat kategori barang inventaris sekolah yang umum.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Elektronik',
                'deskripsi' => 'Peralatan elektronik seperti komputer, printer, proyektor, dll',
            ],
            [
                'nama_kategori' => 'Furniture',
                'deskripsi' => 'Meja, kursi, lemari, rak buku, dll',
            ],
            [
                'nama_kategori' => 'Alat Tulis Kantor',
                'deskripsi' => 'Kertas, pulpen, spidol, penghapus, dll',
            ],
            [
                'nama_kategori' => 'Alat Kebersihan',
                'deskripsi' => 'Sapu, pel, pembersih lantai, dll',
            ],
            [
                'nama_kategori' => 'Peralatan Olahraga',
                'deskripsi' => 'Bola, net, matras, dll',
            ],
            [
                'nama_kategori' => 'Buku Perpustakaan',
                'deskripsi' => 'Buku pelajaran, buku referensi, novel, dll',
            ],
            [
                'nama_kategori' => 'Alat Laboratorium',
                'deskripsi' => 'Peralatan praktikum sains dan komputer',
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
