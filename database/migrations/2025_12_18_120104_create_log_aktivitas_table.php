<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel log aktivitas.
     * Tabel ini mencatat semua aktivitas pengguna dalam sistem.
     */
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis_aktivitas', ['login', 'logout', 'create', 'update', 'delete', 'view']);
            $table->string('nama_tabel', 100)->nullable()->comment('Nama tabel yang terpengaruh');
            $table->unsignedBigInteger('record_id')->nullable()->comment('ID record yang terpengaruh');
            $table->text('deskripsi');
            $table->json('perubahan_data')->nullable()->comment('Data perubahan dalam format JSON');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Index untuk pencarian dan filtering log
            $table->index(['user_id', 'jenis_aktivitas', 'created_at']);
            $table->index(['nama_tabel', 'record_id']);
            $table->index('created_at');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel log_aktivitas.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
