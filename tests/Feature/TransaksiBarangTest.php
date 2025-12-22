<?php

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\TransaksiBarang;
use App\Models\User;

beforeEach(function () {
    $this->user = User::firstOrCreate(
        ['email' => 'testuser@example.com'],
        ['name' => 'Test User', 'password' => bcrypt('password')]
    );
    $this->actingAs($this->user);

    $timestamp = now()->timestamp;

    $this->kategori = Kategori::firstOrCreate(
        ['nama_kategori' => 'Elektronik'],
        ['deskripsi' => 'Kategori elektronik']
    );

    $this->lokasi = Lokasi::firstOrCreate(
        ['nama_lokasi' => 'Ruang Guru'],
        ['alamat' => 'Lantai 1']
    );

    $this->barang = Barang::firstOrCreate(
        ['kode_barang' => "ELE-KO-GU-{$timestamp}"],
        [
            'nama_barang' => 'Komputer',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'satuan' => 'unit',
            'harga_satuan' => 5000000,
            'jumlah_stok' => 100,
        ]
    );
});

it('can create transaksi masuk', function () {
    $timestamp = now()->timestamp;
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => "TM-{$timestamp}-001",
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'masuk',
        'jumlah' => 10,
        'tanggal_transaksi' => now(),
        'user_id' => $this->user->id,
    ]);

    expect($transaksi)->toBeInstanceOf(TransaksiBarang::class)
        ->and($transaksi->tipe_transaksi)->toBe('masuk')
        ->and($transaksi->jumlah)->toBe(10);
});

it('can create transaksi keluar', function () {
    $timestamp = now()->timestamp;
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => "TK-{$timestamp}-001",
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'keluar',
        'jumlah' => 5,
        'tanggal_transaksi' => now(),
        'user_id' => $this->user->id,
    ]);

    expect($transaksi)->toBeInstanceOf(TransaksiBarang::class)
        ->and($transaksi->tipe_transaksi)->toBe('keluar')
        ->and($transaksi->jumlah)->toBe(5);
});

it('saves penanggung_jawab field correctly', function () {
    $timestamp = now()->timestamp;
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => "TM-{$timestamp}-002",
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'masuk',
        'jumlah' => 10,
        'tanggal_transaksi' => now(),
        'penanggung_jawab' => 'John Doe',
        'user_id' => $this->user->id,
    ]);

    expect($transaksi->penanggung_jawab)->toBe('John Doe');
});

it('saves keterangan field correctly', function () {
    $timestamp = now()->timestamp;
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => "TM-{$timestamp}-003",
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'masuk',
        'jumlah' => 10,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pembelian baru',
        'user_id' => $this->user->id,
    ]);

    expect($transaksi->keterangan)->toBe('Pembelian baru');
});

it('has correct database column names', function () {
    $timestamp = now()->timestamp;
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => "TM-{$timestamp}-004",
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'masuk',
        'jumlah' => 10,
        'tanggal_transaksi' => now(),
        'penanggung_jawab' => 'Test',
        'user_id' => $this->user->id,
    ]);

    // Verify the columns exist in database
    expect($transaksi->getAttributes())->toHaveKey('tipe_transaksi')
        ->and($transaksi->getAttributes())->toHaveKey('kode_transaksi')
        ->and($transaksi->getAttributes())->toHaveKey('penanggung_jawab')
        ->and($transaksi->getAttributes())->toHaveKey('user_id')
        ->and($transaksi->tipe_transaksi)->toBe('masuk')
        ->and($transaksi->penanggung_jawab)->toBe('Test');
});

it('updates stock when transaksi masuk is created', function () {
    $initialStock = $this->barang->jumlah_stok;
    $timestamp = now()->timestamp;

    TransaksiBarang::create([
        'kode_transaksi' => "TM-{$timestamp}-005",
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'masuk',
        'jumlah' => 10,
        'tanggal_transaksi' => now(),
        'user_id' => $this->user->id,
        'approval_status' => 'approved',
    ]);

    $this->barang->refresh();
    expect($this->barang->jumlah_stok)->toBe($initialStock + 10);
});

it('updates stock when transaksi keluar is created', function () {
    $initialStock = $this->barang->jumlah_stok;
    $timestamp = now()->timestamp;

    TransaksiBarang::create([
        'kode_transaksi' => "TK-{$timestamp}-006",
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'keluar',
        'jumlah' => 5,
        'tanggal_transaksi' => now(),
        'user_id' => $this->user->id,
    ]);

    $this->barang->refresh();
    expect($this->barang->jumlah_stok)->toBe($initialStock - 5);
});
