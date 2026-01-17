<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel transaksi barang.
     * Tabel ini mencatat setiap transaksi masuk dan keluar barang.
     */
    public function up(): void
    {
        Schema::create('transaksi_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->string('barang_id', 50);
            $table->string('lokasi_id', 50);
            $table->enum('tipe_transaksi', ['masuk'])->default('masuk')->comment('Hanya barang masuk (pembelian)');
            $table->integer('jumlah');
            $table->date('tanggal_transaksi');
            $table->string('penanggung_jawab', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang approve transaksi');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('Pengguna yang melakukan transaksi');
            $table->timestamps();

            // Foreign keys
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
            $table->foreign('lokasi_id')->references('kode_lokasi')->on('lokasi')->cascadeOnDelete();

            // Index untuk pencarian dan reporting
            $table->index(['barang_id', 'lokasi_id']);
            $table->index('tanggal_transaksi');
            $table->index('kode_transaksi');
            $table->index('approval_status');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel transaksi_barang.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_barang');
    }
};
