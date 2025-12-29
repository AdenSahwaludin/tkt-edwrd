<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lokasi>
 */
class LokasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buildings = ['Gedung Utama', 'Gedung Kelas', 'Gedung Laboratorium', 'Gedung Belakang'];

        return [
            'nama_lokasi' => fake()->randomElement([
                'Gudang Penyimpanan',
                'Ruang Kelas A',
                'Ruang Kelas B',
                'Laboratorium',
                'Perpustakaan',
                'Kantor Kepala Sekolah',
                'Ruang TU',
            ]),
            'gedung' => fake()->randomElement($buildings),
            'lantai' => fake()->numberBetween(1, 3),
            'keterangan' => fake()->sentence(6),
        ];
    }
}
