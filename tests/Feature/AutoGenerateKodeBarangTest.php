<?php

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create admin user
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->admin);

    // Create kategori and lokasi
    $this->kategori = Kategori::create([
        'kode_kategori' => 'ELE',
        'nama_kategori' => 'Elektronik',
    ]);

    $this->lokasi = Lokasi::create([
        'kode_lokasi' => 'RG',
        'nama_lokasi' => 'Ruang Guru',
    ]);
});

it('generates kode barang with correct format', function () {
    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $this->lokasi->id,
        'Kursi Lipat'
    );

    expect($kodeBarang)->toBe('ELE-KL-RG-001');
});

it('generates kode barang with single word nama barang', function () {
    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $this->lokasi->id,
        'Meja'
    );

    expect($kodeBarang)->toBe('ELE-ME-RG-001');
});

it('increments sequence number for same prefix', function () {
    // Create first barang
    Barang::create([
        'kode_barang' => 'ELE-KL-RG-001',
        'nama_barang' => 'Kursi Lipat',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 10,
        'reorder_point' => 5,
        'harga_satuan' => 50000,
        'status' => 'baik',
    ]);

    // Generate new kode with same pattern
    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $this->lokasi->id,
        'Kursi Lipat'
    );

    expect($kodeBarang)->toBe('ELE-KL-RG-002');
});

it('auto-generates kode barang when creating barang without kode', function () {
    $barang = Barang::create([
        'nama_barang' => 'Komputer Dell',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 5,
        'reorder_point' => 2,
        'harga_satuan' => 5000000,
        'status' => 'baik',
    ]);

    expect($barang->kode_barang)->not->toBeNull();
    expect($barang->kode_barang)->toMatch('/^ELE-KD-RG-\d{3}$/');
});

it('extracts correct initials from multi-word lokasi', function () {
    $lokasi2 = Lokasi::create([
        'kode_lokasi' => 'LK',
        'nama_lokasi' => 'Lab Komputer',
    ]);

    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $lokasi2->id,
        'Mouse'
    );

    // LK should be extracted from "Lab Komputer"
    expect($kodeBarang)->toBe('ELE-MO-LK-001');
});

it('handles three-word kategori correctly', function () {
    $kategori2 = Kategori::create([
        'kode_kategori' => 'FUR',
        'nama_kategori' => 'Furniture Kantor',
    ]);

    $kodeBarang = Barang::generateKodeBarang(
        $kategori2->id,
        $this->lokasi->id,
        'Meja Tulis'
    );

    // Should take first 3 letters: FUR
    expect($kodeBarang)->toBe('FUR-MT-RG-001');
});
