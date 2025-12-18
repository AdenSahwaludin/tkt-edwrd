<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom role pada tabel users.
     * Role digunakan untuk otorisasi akses: admin, petugas_inventaris, kepala_sekolah.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'petugas_inventaris', 'kepala_sekolah'])
                ->default('petugas_inventaris')
                ->after('email');
            $table->boolean('is_active')->default(true)->after('role');
            $table->softDeletes();

            // Index untuk filtering berdasarkan role
            $table->index('role');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus kolom yang ditambahkan.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};
