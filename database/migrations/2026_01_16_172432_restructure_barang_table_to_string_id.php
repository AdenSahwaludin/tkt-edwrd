<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restructure tabel barang:
     * - Ubah PK dari integer auto-increment ke string custom (HPE-ELE-001)
     * - Hapus kolom stok (pindah ke stok_lokasi)
     * - Hapus kolom lokasi_id (barang bisa di multiple lokasi)
     * - Hapus kolom reorder_point (tidak relevan dengan sistem baru)
     */
    public function up(): void
    {
        // Backup data existing
        DB::statement('CREATE TABLE barang_backup AS SELECT * FROM barang');

        // Drop foreign keys yang terkait dengan barang.id
        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
        });

        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
        });

        // Drop dan recreate tabel barang dengan struktur baru
        Schema::dropIfExists('barang');

        Schema::create('barang', function (Blueprint $table) {
            // Primary key sebagai string (HPE-ELE-001)
            $table->string('id', 50)->primary();
            $table->string('nama_barang', 200);
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->string('satuan', 50)->default('pcs');
            $table->enum('status', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->string('merk', 100)->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index untuk pencarian
            $table->index('nama_barang');
            $table->index('kategori_id');
            $table->index('status');
        });

        // Update transaksi_barang untuk FK string
        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->string('barang_id', 50)->change();
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
        });

        // Update barang_rusaks untuk FK string
        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->string('barang_id', 50)->change();
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
        });

        // Update stok_lokasi untuk FK string ke barang
        if (Schema::hasTable('stok_lokasi')) {
            Schema::table('stok_lokasi', function (Blueprint $table) {
                $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
            });
        }

        // Update transaksi_keluar untuk FK string ke barang
        if (Schema::hasTable('transaksi_keluar')) {
            Schema::table('transaksi_keluar', function (Blueprint $table) {
                $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore dari backup
        Schema::dropIfExists('barang');
        DB::statement('CREATE TABLE barang AS SELECT * FROM barang_backup');
        DB::statement('DROP TABLE barang_backup');

        // Restore foreign keys
        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->unsignedBigInteger('barang_id')->change();
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
        });

        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->unsignedBigInteger('barang_id')->change();
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
        });
    }
};
