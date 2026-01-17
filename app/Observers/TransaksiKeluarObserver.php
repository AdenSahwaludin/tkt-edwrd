<?php

namespace App\Observers;

use App\Models\LogAktivitas;
use App\Models\StokLokasi;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\DB;

class TransaksiKeluarObserver
{
    /**
     * Handle the TransaksiKeluar "creating" event.
     */
    public function creating(TransaksiKeluar $transaksiKeluar): void
    {
        // Auto-generate kode_transaksi (only if tipe is set)
        if (empty($transaksiKeluar->kode_transaksi) && ! empty($transaksiKeluar->tipe)) {
            $transaksiKeluar->kode_transaksi = TransaksiKeluar::generateKodeTransaksi($transaksiKeluar->tipe);
        }

        // Set default approval status
        if (empty($transaksiKeluar->approval_status)) {
            $transaksiKeluar->approval_status = 'pending';
        }
    }

    /**
     * Handle the TransaksiKeluar "created" event.
     */
    public function created(TransaksiKeluar $transaksiKeluar): void
    {
        // Only update stock if already approved (shouldn't happen normally)
        if ($transaksiKeluar->approval_status === 'approved') {
            $this->updateStock($transaksiKeluar);
        }

        // Log activity
        LogAktivitas::create([
            'user_id' => $transaksiKeluar->user_id,
            'jenis_aktivitas' => 'create',
            'nama_tabel' => 'transaksi_keluar',
            'record_id' => $transaksiKeluar->id,
            'deskripsi' => "Transaksi {$transaksiKeluar->tipe} barang {$transaksiKeluar->barang->nama_barang} sejumlah {$transaksiKeluar->jumlah} (status: {$transaksiKeluar->approval_status})",
            'perubahan_data' => $transaksiKeluar->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the TransaksiKeluar "updated" event.
     */
    public function updated(TransaksiKeluar $transaksiKeluar): void
    {
        // Handle approval status changes
        if ($transaksiKeluar->wasChanged('approval_status')) {
            $oldStatus = $transaksiKeluar->getOriginal('approval_status');
            $newStatus = $transaksiKeluar->approval_status;

            // If just approved, update stock
            if ($oldStatus === 'pending' && $newStatus === 'approved') {
                $this->updateStock($transaksiKeluar);
            }
        }

        // Log activity
        LogAktivitas::create([
            'user_id' => auth()->id() ?? $transaksiKeluar->user_id,
            'jenis_aktivitas' => 'update',
            'nama_tabel' => 'transaksi_keluar',
            'record_id' => $transaksiKeluar->id,
            'deskripsi' => "Mengubah transaksi {$transaksiKeluar->kode_transaksi}",
            'perubahan_data' => [
                'before' => $transaksiKeluar->getOriginal(),
                'after' => $transaksiKeluar->getAttributes(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the TransaksiKeluar "deleted" event.
     */
    public function deleted(TransaksiKeluar $transaksiKeluar): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'delete',
            'nama_tabel' => 'transaksi_keluar',
            'record_id' => $transaksiKeluar->id,
            'deskripsi' => "Menghapus transaksi {$transaksiKeluar->kode_transaksi}",
            'perubahan_data' => $transaksiKeluar->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Update stock when transaction is approved.
     */
    protected function updateStock(TransaksiKeluar $transaksiKeluar): void
    {
        DB::transaction(function () use ($transaksiKeluar) {
            // Get source location stock
            $stokAsal = StokLokasi::where('barang_id', $transaksiKeluar->barang_id)
                ->where('lokasi_id', $transaksiKeluar->lokasi_asal_id)
                ->firstOrFail();

            // Reduce stock from source
            $stokAsal->kurangiStok($transaksiKeluar->jumlah);

            // If pemindahan, add to destination
            if ($transaksiKeluar->tipe === 'pemindahan' && $transaksiKeluar->lokasi_tujuan_id) {
                $stokTujuan = StokLokasi::firstOrCreate(
                    [
                        'barang_id' => $transaksiKeluar->barang_id,
                        'lokasi_id' => $transaksiKeluar->lokasi_tujuan_id,
                    ],
                    ['stok' => 0]
                );

                $stokTujuan->tambahStok($transaksiKeluar->jumlah);
            }
        });
    }
}
