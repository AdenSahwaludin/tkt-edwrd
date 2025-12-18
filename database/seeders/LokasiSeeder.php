<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Buat lokasi penyimpanan barang di sekolah.
     */
    public function run(): void
    {
        $lokasis = [
            [
                'nama_lokasi' => 'Ruang Kepala Sekolah',
                'gedung' => 'Gedung Utama',
                'lantai' => '2',
                'keterangan' => 'Ruang kerja kepala sekolah',
            ],
            [
                'nama_lokasi' => 'Ruang Guru',
                'gedung' => 'Gedung Utama',
                'lantai' => '1',
                'keterangan' => 'Ruang kerja para guru',
            ],
            [
                'nama_lokasi' => 'Ruang Kelas 7A',
                'gedung' => 'Gedung Kelas',
                'lantai' => '1',
                'keterangan' => 'Ruang belajar kelas 7A',
            ],
            [
                'nama_lokasi' => 'Laboratorium Komputer',
                'gedung' => 'Gedung Laboratorium',
                'lantai' => '2',
                'keterangan' => 'Lab komputer untuk praktikum',
            ],
            [
                'nama_lokasi' => 'Perpustakaan',
                'gedung' => 'Gedung Utama',
                'lantai' => '2',
                'keterangan' => 'Perpustakaan sekolah',
            ],
            [
                'nama_lokasi' => 'Gudang Penyimpanan',
                'gedung' => 'Gedung Belakang',
                'lantai' => '1',
                'keterangan' => 'Gudang untuk barang-barang cadangan',
            ],
            [
                'nama_lokasi' => 'Ruang Tata Usaha',
                'gedung' => 'Gedung Utama',
                'lantai' => '1',
                'keterangan' => 'Ruang administrasi sekolah',
            ],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}
