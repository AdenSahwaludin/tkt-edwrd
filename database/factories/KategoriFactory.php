<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori>
 */
class KategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategoriNames = [
            'Alat Tulis',
            'Elektronik',
            'Furniture',
            'Alat Kebersihan',
            'Peralatan Laboratorium',
            'Peralatan Olahraga',
        ];

        return [
            'nama_kategori' => fake()->randomElement($kategoriNames),
            'deskripsi' => fake()->sentence(8),
        ];
    }
}
