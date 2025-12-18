<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\LogAktivitas;

class BarangObserver
{
    /**
     * Handle the Barang "creating" event.
     * Auto-generate kode_barang jika belum diisi.
     */
    public function creating(Barang $barang): void
    {
        // Generate kode barang jika belum ada
        if (empty($barang->kode_barang)) {
            $barang->kode_barang = Barang::generateKodeBarang(
                $barang->kategori_id,
                $barang->lokasi_id,
                $barang->nama_barang
            );
        }
    }

    /**
     * Handle the Barang "created" event.
     */
    public function created(Barang $barang): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'create',
            'nama_tabel' => 'barang',
            'record_id' => $barang->id,
            'deskripsi' => "Menambahkan barang baru: {$barang->nama_barang} ({$barang->kode_barang})",
            'perubahan_data' => $barang->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Barang "updated" event.
     */
    public function updated(Barang $barang): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'update',
            'nama_tabel' => 'barang',
            'record_id' => $barang->id,
            'deskripsi' => "Mengubah data barang: {$barang->nama_barang} ({$barang->kode_barang})",
            'perubahan_data' => [
                'before' => $barang->getOriginal(),
                'after' => $barang->getAttributes(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Barang "deleted" event.
     */
    public function deleted(Barang $barang): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'delete',
            'nama_tabel' => 'barang',
            'record_id' => $barang->id,
            'deskripsi' => "Menghapus barang: {$barang->nama_barang} ({$barang->kode_barang})",
            'perubahan_data' => $barang->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Barang "restored" event.
     */
    public function restored(Barang $barang): void
    {
        //
    }

    /**
     * Handle the Barang "force deleted" event.
     */
    public function forceDeleted(Barang $barang): void
    {
        //
    }
}
