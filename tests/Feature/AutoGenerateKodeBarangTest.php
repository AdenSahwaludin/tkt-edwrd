<?php

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;

beforeEach(function () {
    // Create or get admin user
    $this->admin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        ['name' => 'Admin User', 'password' => bcrypt('password')]
    );
    $this->actingAs($this->admin);

    // Create or get kategori and lokasi
    $this->kategori = Kategori::firstOrCreate(
        ['nama_kategori' => 'Elektronik'],
        []
    );

    $this->lokasi = Lokasi::firstOrCreate(
        ['nama_lokasi' => 'Ruang Guru'],
        []
    );
});

it('generates kode barang with correct format', function () {
    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $this->lokasi->id,
        'Kursi Lipat'
    );

    // "Ruang Guru" now extracts to "GU" (skip "Ruang", take "Guru")
    // Should start with ELE-KL-GU
    expect($kodeBarang)->toMatch('/^ELE-KL-GU-\d{3}$/');
});

it('generates kode barang with single word nama barang', function () {
    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $this->lokasi->id,
        'Meja'
    );

    expect($kodeBarang)->toMatch('/^ELE-ME-GU-\d{3}$/');
});

it('increments sequence number for same prefix', function () {
    // Create first barang with unique code
    $timestamp = now()->timestamp;
    $kode1 = "ELE-KL-GU-{$timestamp}";

    Barang::firstOrCreate(
        ['kode_barang' => $kode1],
        [
            'nama_barang' => 'Kursi Lipat',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'jumlah_stok' => 10,
            'reorder_point' => 5,
            'harga_satuan' => 50000,
            'status' => 'baik',
        ]
    );

    // Generate new kode with same pattern
    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $this->lokasi->id,
        'Kursi Lipat'
    );

    // Should have incremented sequence number
    expect($kodeBarang)->toMatch('/^ELE-KL-GU-\d{3}$/');
    expect($kodeBarang)->not->toBe($kode1);
});

it('auto-generates kode barang when creating barang without kode', function () {
    $barang = Barang::create([
        'nama_barang' => 'Komputer Dell '.now()->timestamp,
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 5,
        'reorder_point' => 2,
        'harga_satuan' => 5000000,
        'status' => 'baik',
    ]);

    expect($barang->kode_barang)->not->toBeNull();
    expect($barang->kode_barang)->toMatch('/^ELE-KD-GU-\d{3}$/');
});

it('extracts correct code from multi-word lokasi with common words', function () {
    $lokasi2 = Lokasi::firstOrCreate(
        ['nama_lokasi' => 'Lab Komputer'],
        []
    );

    $kodeBarang = Barang::generateKodeBarang(
        $this->kategori->id,
        $lokasi2->id,
        'Mouse'
    );

    // Should extract "KO" from "Lab Komputer" (skip "Lab", take "Komputer")
    expect($kodeBarang)->toMatch('/^ELE-MO-KO-\d{3}$/');
});

it('differentiates similar lokasi names', function () {
    $lokasiKepala = Lokasi::firstOrCreate(
        ['nama_lokasi' => 'Ruang Kepala Sekolah'],
        []
    );

    $lokasiKelas = Lokasi::firstOrCreate(
        ['nama_lokasi' => 'Ruang Kelas 7A'],
        []
    );

    $kodeKepala = Barang::generateKodeBarang($this->kategori->id, $lokasiKepala->id, 'Meja');
    $kodeKelas = Barang::generateKodeBarang($this->kategori->id, $lokasiKelas->id, 'Meja');

    // Should be different: KS vs K7
    expect($kodeKepala)->toMatch('/^ELE-ME-KS-\d{3}$/');
    expect($kodeKelas)->toMatch('/^ELE-ME-K7-\d{3}$/');
    expect($kodeKepala)->not->toBe($kodeKelas);
});

it('handles three-word kategori correctly', function () {
    $kategori2 = Kategori::firstOrCreate(
        ['nama_kategori' => 'Furniture Kantor'],
        []
    );

    $kodeBarang = Barang::generateKodeBarang(
        $kategori2->id,
        $this->lokasi->id,
        'Meja Tulis'
    );

    // Should take first 3 letters: FUR, and GU from "Ruang Guru"
    expect($kodeBarang)->toMatch('/^FUR-MT-GU-\d{3}$/');
});
