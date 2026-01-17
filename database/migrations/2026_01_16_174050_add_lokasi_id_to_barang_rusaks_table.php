<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom lokasi_id ke barang_rusaks.
     * Barang rusak harus terhubung ke lokasi tertentu.
     */
    public function up(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->string('lokasi_id', 50)->after('barang_id');
            $table->foreign('lokasi_id')->references('kode_lokasi')->on('lokasi')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_rusaks', function (Blueprint $table) {
            $table->dropForeign(['lokasi_id']);
            $table->dropColumn('lokasi_id');
        });
    }
};
