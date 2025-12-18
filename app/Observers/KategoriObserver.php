<?php

namespace App\Observers;

use App\Models\Kategori;
use App\Models\LogAktivitas;

class KategoriObserver
{
    /**
     * Handle the Kategori "created" event.
     */
    public function created(Kategori $kategori): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'create',
            'nama_tabel' => 'kategori',
            'record_id' => $kategori->id,
            'deskripsi' => "Menambahkan kategori baru: {$kategori->nama_kategori}",
            'perubahan_data' => $kategori->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Kategori "updated" event.
     */
    public function updated(Kategori $kategori): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'update',
            'nama_tabel' => 'kategori',
            'record_id' => $kategori->id,
            'deskripsi' => "Mengubah data kategori: {$kategori->nama_kategori}",
            'perubahan_data' => [
                'before' => $kategori->getOriginal(),
                'after' => $kategori->getAttributes(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Kategori "deleted" event.
     */
    public function deleted(Kategori $kategori): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'delete',
            'nama_tabel' => 'kategori',
            'record_id' => $kategori->id,
            'deskripsi' => "Menghapus kategori: {$kategori->nama_kategori}",
            'perubahan_data' => $kategori->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Kategori "restored" event.
     */
    public function restored(Kategori $kategori): void
    {
        //
    }

    /**
     * Handle the Kategori "force deleted" event.
     */
    public function forceDeleted(Kategori $kategori): void
    {
        //
    }
}
