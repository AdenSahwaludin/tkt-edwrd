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
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->enum('tipe_transaksi', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->date('tanggal_transaksi');
            $table->string('penanggung_jawab', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('Pengguna yang melakukan transaksi');
            $table->timestamps();

            // Index untuk pencarian dan reporting
            $table->index(['barang_id', 'tipe_transaksi']);
            $table->index('tanggal_transaksi');
            $table->index('kode_transaksi');
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
