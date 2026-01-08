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
        $namaBarang = [
            'Monitor LED 24 Inch',
            'Keyboard Mekanik RGB',
            'Mouse Wireless Logitech',
            'Headset Gaming Hyperx',
            'Printer Canon Pixma',
            'Scanner Dokumen Epson',
            'Webcam Logitech 1080p',
            'USB Hub 7 Port',
            'Kabel HDMI Premium',
            'Power Bank 20000mAh',
            'Cooling Fan Laptop',
            'SSD 256GB Samsung',
            'RAM DDR4 8GB',
            'HDD Eksternal 1TB',
            'Laptop Asus VivoBook',
            'CPU Desktop Intel i5',
            'Motherboard Asrock',
            'PSU 650W Gold',
            'Casing PC ATX Tempered',
            'Thermal Paste Arctic',
            'Cable Ties Velcro',
            'Adaptor USB Type-C',
            'Screen Protector Tempered',
            'Keyboard Logitech Wireless',
            'Mouse Pad Gaming XXL',
            'Monitor Curved 27 Inch',
            'Stand Monitor Adjustable',
            'Lighting LED Strip RGB',
            'Charger Laptop 65W',
            'Dock Station USB-C',
        ];

        return [
            'kode_barang' => 'BRG'.str_pad($kodeCounter, 6, '0', STR_PAD_LEFT),
            'nama_barang' => fake()->randomElement($namaBarang),
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
