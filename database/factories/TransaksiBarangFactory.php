<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransaksiBarang>
 */
class TransaksiBarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $kodeCounter = 0;
        $kodeCounter++;

        $tipeTransaksi = ['masuk', 'keluar'];
        $tipe = fake()->randomElement($tipeTransaksi);

        return [
            'kode_transaksi' => 'TRX'.($tipe === 'masuk' ? '-IN-' : '-OUT-').str_pad($kodeCounter, 6, '0', STR_PAD_LEFT),
            'barang_id' => Barang::inRandomOrder()->first()?->id ?? Barang::factory(),
            'tipe_transaksi' => $tipe,
            'jumlah' => fake()->numberBetween(1, 20),
            'tanggal_transaksi' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'penanggung_jawab' => fake()->name(),
            'keterangan' => fake()->sentence(5),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'approved_by' => fake()->boolean(70) ? (User::inRandomOrder()->first()?->id ?? User::factory()) : null,
            'approved_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-3 months', 'now') : null,
            'approval_status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'approval_notes' => fake()->boolean(50) ? fake()->sentence(4) : null,
        ];
    }

    /**
     * Indicate that transaksi status is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that transaksi is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'approved',
            'approved_by' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that transaksi is masuk (input).
     */
    public function masuk(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_transaksi' => 'masuk',
        ]);
    }

    /**
     * Indicate that transaksi is keluar (output).
     */
    public function keluar(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_transaksi' => 'keluar',
        ]);
    }
}
