<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel lokasi penyimpanan barang.
     * Tabel ini menyimpan informasi ruangan atau tempat penyimpanan barang.
     * Struktur baru: kode_lokasi string sebagai PK (GD, LB, KLS, dll).
     */
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->string('kode_lokasi', 50)->primary();
            $table->string('nama_lokasi', 100);
            $table->string('gedung', 50)->nullable();
            $table->string('lantai', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index untuk pencarian yang lebih cepat
            $table->index('nama_lokasi');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel lokasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
