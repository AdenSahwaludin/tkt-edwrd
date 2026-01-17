<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel barang inventaris.
     * Struktur baru: ID string (HPE-ELE-001), tanpa stok (pindah ke stok_lokasi).
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('nama_barang', 200);
            $table->string('kategori_id', 50);
            $table->foreign('kategori_id')->references('kode_kategori')->on('kategori')->cascadeOnDelete();
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
    }

    /**
     * Batalkan migrasi dengan menghapus tabel barang.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
