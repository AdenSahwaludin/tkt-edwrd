<?php

namespace App\Observers;

use App\Models\BarangRusak;

class BarangRusakObserver
{
    /**
     * Handle the BarangRusak "created" event.
     * Kurangi stok barang saat pencatatan barang rusak dibuat.
     */
    public function created(BarangRusak $barangRusak): void
    {
        $barangRusak->barang()->decrement('jumlah_stok', $barangRusak->jumlah);
    }

    /**
     * Handle the BarangRusak "updated" event.
     * Sesuaikan stok barang jika jumlah barang rusak diubah.
     */
    public function updated(BarangRusak $barangRusak): void
    {
        $originalJumlah = $barangRusak->getOriginal('jumlah');
        $difference = $barangRusak->jumlah - $originalJumlah;

        if ($difference !== 0) {
            $barangRusak->barang()->decrement('jumlah_stok', $difference);
        }
    }

    /**
     * Handle the BarangRusak "deleted" event.
     * Kembalikan stok barang saat pencatatan barang rusak dihapus.
     */
    public function deleted(BarangRusak $barangRusak): void
    {
        $barangRusak->barang()->increment('jumlah_stok', $barangRusak->jumlah);
    }

    /**
     * Handle the BarangRusak "restored" event.
     */
    public function restored(BarangRusak $barangRusak): void
    {
        //
    }

    /**
     * Handle the BarangRusak "force deleted" event.
     */
    public function forceDeleted(BarangRusak $barangRusak): void
    {
        //
    }
}
