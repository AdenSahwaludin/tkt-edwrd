<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel pivot stok_lokasi untuk tracking stok barang per lokasi.
     * Satu barang bisa ada di multiple lokasi dengan stok berbeda.
     */
    public function up(): void
    {
        Schema::create('stok_lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('barang_id', 50);
            $table->string('lokasi_id', 50);
            $table->integer('stok')->default(0)->comment('Jumlah stok barang di lokasi ini');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
            $table->foreign('lokasi_id')->references('kode_lokasi')->on('lokasi')->cascadeOnDelete();

            // Unique constraint: satu barang hanya punya 1 record per lokasi
            $table->unique(['barang_id', 'lokasi_id']);

            // Index untuk query cepat
            $table->index('barang_id');
            $table->index('lokasi_id');
            $table->index('stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_lokasi');
    }
};
