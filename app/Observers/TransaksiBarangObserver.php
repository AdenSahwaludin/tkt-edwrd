<?php

namespace App\Observers;

use App\Models\LogAktivitas;
use App\Models\StokLokasi;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\DB;

class TransaksiBarangObserver
{
    /**
     * Handle the TransaksiBarang "creating" event.
     * Set default values.
     */
    public function creating(TransaksiBarang $transaksiBarang): void
    {
        // TransaksiBarang is only for 'masuk' (pembelian)
        $transaksiBarang->tipe_transaksi = 'masuk';

        // Set default approval status
        if (empty($transaksiBarang->approval_status)) {
            $transaksiBarang->approval_status = 'pending';
        }
    }

    /**
     * Handle the TransaksiBarang "created" event.
     * Update stok_lokasi dan catat log aktivitas.
     */
    public function created(TransaksiBarang $transaksiBarang): void
    {
        DB::transaction(function () use ($transaksiBarang) {
            $barang = $transaksiBarang->barang;

            // Only update stock if already approved
            if ($transaksiBarang->approval_status === 'approved') {
                // Find or create stok_lokasi
                $stokLokasi = StokLokasi::firstOrCreate(
                    [
                        'barang_id' => $transaksiBarang->barang_id,
                        'lokasi_id' => $transaksiBarang->lokasi_id,
                    ],
                    ['stok' => 0]
                );

                // Tambah stok
                $stokLokasi->tambahStok($transaksiBarang->jumlah);
            }

            // Catat log aktivitas
            $statusMessage = $transaksiBarang->approval_status === 'pending'
                ? ' (menunggu persetujuan)'
                : '';

            LogAktivitas::create([
                'user_id' => $transaksiBarang->user_id,
                'jenis_aktivitas' => 'create',
                'nama_tabel' => 'transaksi_barang',
                'record_id' => $transaksiBarang->id,
                'deskripsi' => "Transaksi masuk barang {$barang->nama_barang} sejumlah {$transaksiBarang->jumlah} {$barang->satuan}{$statusMessage}",
                'perubahan_data' => $transaksiBarang->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }

    /**
     * Handle the TransaksiBarang "updated" event.
     */
    public function updated(TransaksiBarang $transaksiBarang): void
    {
        // Handle approval status changes
        if ($transaksiBarang->wasChanged('approval_status')) {
            $oldStatus = $transaksiBarang->getOriginal('approval_status');
            $newStatus = $transaksiBarang->approval_status;

            // If just approved, update stock
            if ($oldStatus === 'pending' && $newStatus === 'approved') {
                $stokLokasi = StokLokasi::firstOrCreate(
                    [
                        'barang_id' => $transaksiBarang->barang_id,
                        'lokasi_id' => $transaksiBarang->lokasi_id,
                    ],
                    ['stok' => 0]
                );

                $stokLokasi->tambahStok($transaksiBarang->jumlah);
            }
        }

        // Log changes
        $description = "Mengubah transaksi barang {$transaksiBarang->kode_transaksi}";

        if ($transaksiBarang->wasChanged('approval_status')) {
            $oldStatus = $transaksiBarang->getOriginal('approval_status');
            $newStatus = $transaksiBarang->approval_status;
            $description .= " - status approval berubah dari {$oldStatus} menjadi {$newStatus}";
        }

        LogAktivitas::create([
            'user_id' => $transaksiBarang->user_id ?? auth()->id(),
            'jenis_aktivitas' => 'update',
            'nama_tabel' => 'transaksi_barang',
            'record_id' => $transaksiBarang->id,
            'deskripsi' => $description,
            'perubahan_data' => [
                'before' => $transaksiBarang->getOriginal(),
                'after' => $transaksiBarang->getAttributes(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the TransaksiBarang "deleted" event.
     */
    public function deleted(TransaksiBarang $transaksiBarang): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'delete',
            'nama_tabel' => 'transaksi_barang',
            'record_id' => $transaksiBarang->id,
            'deskripsi' => "Menghapus transaksi barang {$transaksiBarang->kode_transaksi}",
            'perubahan_data' => $transaksiBarang->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the TransaksiBarang "restored" event.
     */
    public function restored(TransaksiBarang $transaksiBarang): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'restore',
            'nama_tabel' => 'transaksi_barang',
            'record_id' => $transaksiBarang->id,
            'deskripsi' => "Memulihkan transaksi barang {$transaksiBarang->kode_transaksi}",
            'perubahan_data' => $transaksiBarang->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
