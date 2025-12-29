<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Lokasi;
use App\Models\TransaksiBarang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     * Run migrations dan seeder sebelum test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run semua seeder untuk setup test data
        $this->artisan('db:seed');
    }

    /**
     * Test bahwa seeder membuat data dengan benar.
     */
    public function test_seeder_creates_users(): void
    {
        $this->assertGreaterThan(0, User::count());
        $this->assertTrue(User::where('email', 'admin@inventaris.test')->exists());
    }

    public function test_seeder_creates_kategori(): void
    {
        $this->assertGreaterThan(0, Kategori::count());
        $this->assertTrue(Kategori::where('nama_kategori', 'Elektronik')->exists());
    }

    public function test_seeder_creates_lokasi(): void
    {
        $this->assertGreaterThan(0, Lokasi::count());
        $this->assertTrue(Lokasi::where('nama_lokasi', 'Gudang Penyimpanan')->exists());
    }

    public function test_seeder_creates_barang(): void
    {
        $this->assertGreaterThan(0, Barang::count());

        $barang = Barang::first();
        $this->assertNotNull($barang->kode_barang);
        $this->assertNotNull($barang->nama_barang);
        $this->assertNotNull($barang->kategori_id);
        $this->assertNotNull($barang->lokasi_id);
    }

    public function test_seeder_creates_transaksi(): void
    {
        $this->assertGreaterThan(0, TransaksiBarang::count());

        $masuk = TransaksiBarang::where('tipe_transaksi', 'masuk')->count();
        $keluar = TransaksiBarang::where('tipe_transaksi', 'keluar')->count();

        $this->assertGreaterThan(0, $masuk);
        $this->assertGreaterThan(0, $keluar);
    }

    public function test_seeder_creates_log_aktivitas(): void
    {
        $this->assertGreaterThan(0, LogAktivitas::count());

        $log = LogAktivitas::first();
        // user_id bisa null untuk beberapa log, tapi jenis_aktivitas harus ada
        $this->assertNotNull($log->jenis_aktivitas);
    }

    public function test_barang_has_correct_relationships(): void
    {
        $barang = Barang::first();

        $this->assertNotNull($barang->kategori);
        $this->assertNotNull($barang->lokasi);
        $this->assertInstanceOf(Kategori::class, $barang->kategori);
        $this->assertInstanceOf(Lokasi::class, $barang->lokasi);
    }

    public function test_transaksi_has_correct_relationships(): void
    {
        $transaksi = TransaksiBarang::first();

        $this->assertNotNull($transaksi->barang);
        $this->assertNotNull($transaksi->user);
        $this->assertInstanceOf(Barang::class, $transaksi->barang);
        $this->assertInstanceOf(User::class, $transaksi->user);
    }

    public function test_users_have_roles(): void
    {
        $admin = User::where('email', 'admin@inventaris.test')->first();
        $this->assertTrue($admin->hasRole('Admin Sistem'));

        $petugas = User::where('email', 'petugas@inventaris.test')->first();
        $this->assertTrue($petugas->hasRole('Petugas Inventaris'));

        $kepala = User::where('email', 'kepala@inventaris.test')->first();
        $this->assertTrue($kepala->hasRole('Kepala Sekolah'));
    }

    public function test_barang_stok_is_positive(): void
    {
        $barang = Barang::all();

        foreach ($barang as $item) {
            $this->assertGreaterThanOrEqual(0, $item->jumlah_stok);
        }
    }

    public function test_transaksi_jumlah_is_valid(): void
    {
        $transaksi = TransaksiBarang::all();

        foreach ($transaksi as $item) {
            $this->assertGreaterThan(0, $item->jumlah);
        }
    }

    public function test_kategori_lokasi_count(): void
    {
        // Verify the expected counts from seeder
        $this->assertGreaterThanOrEqual(7, Kategori::count());
        $this->assertGreaterThanOrEqual(5, Lokasi::count());
    }

    public function test_users_count(): void
    {
        // Verify we have at least 3 users from seeder
        $this->assertGreaterThanOrEqual(3, User::count());
    }

    public function test_barang_count(): void
    {
        // Verify we have at least 50 barang from factory
        $this->assertGreaterThanOrEqual(50, Barang::count());
    }

    public function test_transaksi_approved_status(): void
    {
        $approved = TransaksiBarang::where('approval_status', 'approved')->count();
        $pending = TransaksiBarang::where('approval_status', 'pending')->count();

        // Should have some approved and some pending
        $this->assertGreaterThan(0, $approved);
        $this->assertGreaterThan(0, $pending);
    }
}
