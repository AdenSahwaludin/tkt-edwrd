<?php

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\TransaksiBarang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $kategori = Kategori::create([
        'kode_kategori' => 'ELE',
        'nama_kategori' => 'Elektronik',
        'deskripsi' => 'Kategori elektronik',
    ]);

    $lokasi = Lokasi::create([
        'kode_lokasi' => 'GU',
        'nama_lokasi' => 'Ruang Guru',
        'alamat' => 'Lantai 1',
    ]);

    $this->barang = Barang::create([
        'kode_barang' => 'ELE-KO-GU-001',
        'nama_barang' => 'Komputer',
        'kategori_id' => $kategori->id,
        'lokasi_id' => $lokasi->id,
        'satuan' => 'unit',
        'harga_satuan' => 5000000,
        'jumlah_stok' => 100,
    ]);
});

it('can create transaksi masuk', function () {
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => 'TM-20251218-001',
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
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => 'TK-20251218-001',
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
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => 'TM-20251218-002',
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
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => 'TM-20251218-003',
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
    $transaksi = TransaksiBarang::create([
        'kode_transaksi' => 'TM-20251218-004',
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

    TransaksiBarang::create([
        'kode_transaksi' => 'TM-20251218-005',
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

    TransaksiBarang::create([
        'kode_transaksi' => 'TK-20251218-006',
        'barang_id' => $this->barang->id,
        'tipe_transaksi' => 'keluar',
        'jumlah' => 5,
        'tanggal_transaksi' => now(),
        'user_id' => $this->user->id,
    ]);

    $this->barang->refresh();
    expect($this->barang->jumlah_stok)->toBe($initialStock - 5);
});
