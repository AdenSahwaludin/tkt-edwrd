<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel barang inventaris.
     * Tabel ini menyimpan data barang dengan tracking stok dan reorder point.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->unique();
            $table->string('nama_barang', 200);
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->constrained('lokasi')->cascadeOnDelete();
            $table->integer('jumlah_stok')->default(0);
            $table->integer('reorder_point')->default(10)->comment('Batas minimal stok sebelum perlu pemesanan ulang');
            $table->string('satuan', 50)->default('pcs');
            $table->enum('status', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->string('merk', 100)->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index untuk pencarian dan query yang lebih cepat
            $table->index(['kode_barang', 'nama_barang']);
            $table->index('status');
            $table->index('jumlah_stok');
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
