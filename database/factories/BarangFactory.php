<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
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

        $satuan = ['pcs', 'box', 'rim', 'dus', 'meter', 'kg', 'unit'];
        $status = ['baik', 'rusak', 'hilang'];
        $merk = ['Logitech', 'Canon', 'Samsung', 'LG', 'Dell', 'HP', 'Brother', 'Epson'];

        return [
            'kode_barang' => 'BRG'.str_pad($kodeCounter, 6, '0', STR_PAD_LEFT),
            'nama_barang' => fake()->words(3, true),
            'kategori_id' => Kategori::inRandomOrder()->first()?->id ?? Kategori::factory(),
            'lokasi_id' => Lokasi::inRandomOrder()->first()?->id ?? Lokasi::factory(),
            'jumlah_stok' => fake()->numberBetween(5, 200),
            'reorder_point' => fake()->numberBetween(5, 20),
            'satuan' => fake()->randomElement($satuan),
            'status' => fake()->randomElement($status),
            'deskripsi' => fake()->sentence(6),
            'harga_satuan' => fake()->numberBetween(10000, 500000),
            'merk' => fake()->randomElement($merk),
            'tanggal_pembelian' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
