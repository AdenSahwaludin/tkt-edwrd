<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel laporan.
     * Tabel ini menyimpan riwayat laporan PDF yang telah dibuat.
     */
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->string('judul_laporan', 200);
            $table->enum('jenis_laporan', ['inventaris_bulanan', 'barang_rusak', 'barang_hilang']);
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->string('file_path')->nullable()->comment('Path ke file PDF yang dihasilkan');
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Index untuk pencarian laporan
            $table->index(['jenis_laporan', 'periode_awal', 'periode_akhir']);
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel laporan.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
