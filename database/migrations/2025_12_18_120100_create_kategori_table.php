<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel kategori barang.
     * Tabel ini menyimpan kategori/jenis barang dalam sistem inventaris.
     * Struktur baru: kode_kategori string sebagai PK (ELE, FUR, ALK, dll).
     */
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->string('kode_kategori', 50)->primary();
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index untuk pencarian yang lebih cepat
            $table->index('nama_kategori');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel kategori.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
