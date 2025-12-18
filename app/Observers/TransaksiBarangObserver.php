<?php

namespace App\Observers;

use App\Models\LogAktivitas;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\DB;

class TransaksiBarangObserver
{
    /**
     * Handle the TransaksiBarang "creating" event.
     * Validasi stok sebelum transaksi keluar dibuat.
     */
    public function creating(TransaksiBarang $transaksiBarang): void
    {
        if ($transaksiBarang->tipe_transaksi === 'keluar') {
            $barang = $transaksiBarang->barang;

            if ($barang->jumlah_stok < $transaksiBarang->jumlah) {
                throw new \Exception(
                    "Stok tidak mencukupi! Stok tersedia: {$barang->jumlah_stok} {$barang->satuan}, diminta: {$transaksiBarang->jumlah} {$barang->satuan}"
                );
            }
        }
    }

    /**
     * Handle the TransaksiBarang "created" event.
     * Update stok barang dan catat log aktivitas.
     */
    public function created(TransaksiBarang $transaksiBarang): void
    {
        DB::transaction(function () use ($transaksiBarang) {
            $barang = $transaksiBarang->barang;

            // Update stok berdasarkan tipe transaksi
            if ($transaksiBarang->tipe_transaksi === 'masuk') {
                $barang->increment('jumlah_stok', $transaksiBarang->jumlah);
            } else {
                $barang->decrement('jumlah_stok', $transaksiBarang->jumlah);
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => $transaksiBarang->user_id,
                'jenis_aktivitas' => 'create',
                'nama_tabel' => 'transaksi_barang',
                'record_id' => $transaksiBarang->id,
                'deskripsi' => "Transaksi {$transaksiBarang->tipe_transaksi} barang {$barang->nama_barang} sejumlah {$transaksiBarang->jumlah} {$barang->satuan}",
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
        LogAktivitas::create([
            'user_id' => $transaksiBarang->user_id ?? auth()->id(),
            'jenis_aktivitas' => 'update',
            'nama_tabel' => 'transaksi_barang',
            'record_id' => $transaksiBarang->id,
            'deskripsi' => "Mengubah transaksi barang {$transaksiBarang->kode_transaksi}",
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
        //
    }

    /**
     * Handle the TransaksiBarang "force deleted" event.
     */
    public function forceDeleted(TransaksiBarang $transaksiBarang): void
    {
        //
    }
}
