<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel transaksi_keluar untuk tracking pengeluaran/pemindahan barang.
     * Mencakup: pemindahan lokasi, peminjaman, penggunaan, penghapusan dari stok.
     */
    public function up(): void
    {
        Schema::create('transaksi_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->enum('tipe', ['pemindahan', 'peminjaman', 'penggunaan', 'penghapusan'])
                ->comment('Jenis transaksi keluar');
            $table->string('barang_id', 50);
            $table->string('lokasi_asal_id', 50);
            $table->string('lokasi_tujuan_id', 50)->nullable()
                ->comment('Null jika bukan pemindahan');
            $table->integer('jumlah');
            $table->date('tanggal_transaksi');
            $table->string('penanggung_jawab', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('user_id')->constrained('users')->comment('User yang input transaksi');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
            $table->foreign('lokasi_asal_id')->references('kode_lokasi')->on('lokasi');
            $table->foreign('lokasi_tujuan_id')->references('kode_lokasi')->on('lokasi');

            // Index
            $table->index('kode_transaksi');
            $table->index('barang_id');
            $table->index('tipe');
            $table->index('tanggal_transaksi');
            $table->index('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar');
    }
};
