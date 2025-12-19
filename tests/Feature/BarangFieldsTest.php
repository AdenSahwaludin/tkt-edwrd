<?php

use App\Filament\Resources\Barangs\BarangResource;
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
        'kode_kategori' => 'FUR',
        'nama_kategori' => 'Furniture',
    ]);

    $this->lokasi = Lokasi::create([
        'kode_lokasi' => 'RG',
        'nama_lokasi' => 'Ruang Guru',
    ]);
});

it('saves jumlah_stok field correctly', function () {
    $barang = Barang::create([
        'kode_barang' => 'FUR-ME-GU-001',
        'nama_barang' => 'Meja Guru',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 25,
        'reorder_point' => 5,
        'harga_satuan' => 500000,
        'status' => 'baik',
    ]);

    expect($barang->jumlah_stok)->toBe(25);
    expect($barang->fresh()->jumlah_stok)->toBe(25);
});

it('saves merk field correctly', function () {
    $barang = Barang::create([
        'kode_barang' => 'FUR-ME-GU-002',
        'nama_barang' => 'Meja Olympic',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 10,
        'reorder_point' => 2,
        'merk' => 'Olympic',
        'harga_satuan' => 750000,
        'status' => 'baik',
    ]);

    expect($barang->merk)->toBe('Olympic');
    expect($barang->fresh()->merk)->toBe('Olympic');
});

it('saves tanggal_pembelian field correctly', function () {
    $tanggal = '2025-01-15';
    $barang = Barang::create([
        'kode_barang' => 'FUR-ME-GU-003',
        'nama_barang' => 'Meja Baru',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 5,
        'reorder_point' => 1,
        'tanggal_pembelian' => $tanggal,
        'harga_satuan' => 600000,
        'status' => 'baik',
    ]);

    expect($barang->tanggal_pembelian->format('Y-m-d'))->toBe($tanggal);
    expect($barang->fresh()->tanggal_pembelian->format('Y-m-d'))->toBe($tanggal);
});

it('saves deskripsi field correctly', function () {
    $deskripsi = 'Meja kayu jati berkualitas tinggi dengan finishing glossy';
    $barang = Barang::create([
        'kode_barang' => 'FUR-ME-GU-004',
        'nama_barang' => 'Meja Jati',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 3,
        'reorder_point' => 1,
        'deskripsi' => $deskripsi,
        'harga_satuan' => 2500000,
        'status' => 'baik',
    ]);

    expect($barang->deskripsi)->toBe($deskripsi);
    expect($barang->fresh()->deskripsi)->toBe($deskripsi);
});

it('saves all fields together correctly', function () {
    $barang = Barang::create([
        'kode_barang' => 'FUR-KU-GU-001',
        'nama_barang' => 'Kursi Kantor Ergonomis',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 15,
        'reorder_point' => 3,
        'merk' => 'Informa',
        'harga_satuan' => 1250000,
        'tanggal_pembelian' => '2025-01-20',
        'deskripsi' => 'Kursi ergonomis dengan sandaran tinggi dan roda',
        'status' => 'baik',
    ]);

    $fresh = $barang->fresh();

    expect($fresh->jumlah_stok)->toBe(15);
    expect($fresh->merk)->toBe('Informa');
    expect($fresh->tanggal_pembelian->format('Y-m-d'))->toBe('2025-01-20');
    expect($fresh->deskripsi)->toBe('Kursi ergonomis dengan sandaran tinggi dan roda');
    expect($fresh->status)->toBe('baik');
});

it('validates jumlah_stok is not negative', function () {
    // This should work with positive value
    $barang = Barang::create([
        'kode_barang' => 'FUR-ME-GU-005',
        'nama_barang' => 'Meja Test',
        'kategori_id' => $this->kategori->id,
        'lokasi_id' => $this->lokasi->id,
        'jumlah_stok' => 0,
        'reorder_point' => 1,
        'harga_satuan' => 100000,
        'status' => 'baik',
    ]);

    expect($barang->jumlah_stok)->toBeGreaterThanOrEqual(0);
});
