<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogAktivitas>
 */
class LogAktivitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisAktivitas = ['create', 'read', 'update', 'delete', 'login', 'logout'];
        $namaTabel = ['barang', 'transaksi_barang', 'kategori', 'lokasi', 'user'];
        $jenis = fake()->randomElement($jenisAktivitas);

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'jenis_aktivitas' => $jenis,
            'nama_tabel' => fake()->randomElement($namaTabel),
            'record_id' => fake()->numberBetween(1, 100),
            'deskripsi' => fake()->sentence(6),
            'perubahan_data' => json_encode([
                'old' => [
                    'nama' => fake()->words(2, true),
                    'status' => 'active',
                ],
                'new' => [
                    'nama' => fake()->words(2, true),
                    'status' => 'inactive',
                ],
            ]),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that activity is create action.
     */
    public function createActivity(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_aktivitas' => 'create',
        ]);
    }

    /**
     * Indicate that activity is update action.
     */
    public function updateActivity(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_aktivitas' => 'update',
        ]);
    }

    /**
     * Indicate that activity is delete action.
     */
    public function deleteActivity(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_aktivitas' => 'delete',
        ]);
    }

    /**
     * Indicate that activity is login action.
     */
    public function login(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_aktivitas' => 'login',
            'nama_tabel' => 'user',
            'record_id' => null,
        ]);
    }
}
