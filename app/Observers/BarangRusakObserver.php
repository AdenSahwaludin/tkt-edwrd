<?php

namespace App\Observers;

use App\Models\BarangRusak;
use App\Models\StokLokasi;

class BarangRusakObserver
{
    /**
     * Handle the BarangRusak "created" event.
     * Kurangi stok barang di lokasi saat pencatatan barang rusak dibuat.
     */
    public function created(BarangRusak $barangRusak): void
    {
        $stokLokasi = StokLokasi::where('barang_id', $barangRusak->barang_id)
            ->where('lokasi_id', $barangRusak->lokasi_id)
            ->first();

        if ($stokLokasi) {
            $stokLokasi->kurangiStok($barangRusak->jumlah);
        }
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
            $stokLokasi = StokLokasi::where('barang_id', $barangRusak->barang_id)
                ->where('lokasi_id', $barangRusak->lokasi_id)
                ->first();

            if ($stokLokasi) {
                if ($difference > 0) {
                    $stokLokasi->kurangiStok($difference);
                } else {
                    $stokLokasi->tambahStok(abs($difference));
                }
            }
        }
    }

    /**
     * Handle the BarangRusak "deleted" event.
     * Kembalikan stok barang saat pencatatan barang rusak dihapus.
     */
    public function deleted(BarangRusak $barangRusak): void
    {
        $stokLokasi = StokLokasi::where('barang_id', $barangRusak->barang_id)
            ->where('lokasi_id', $barangRusak->lokasi_id)
            ->first();

        if ($stokLokasi) {
            $stokLokasi->tambahStok($barangRusak->jumlah);
        }
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
