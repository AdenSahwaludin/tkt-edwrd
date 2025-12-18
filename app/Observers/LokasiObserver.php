<?php

namespace App\Observers;

use App\Models\LogAktivitas;
use App\Models\Lokasi;

class LokasiObserver
{
    /**
     * Handle the Lokasi "created" event.
     */
    public function created(Lokasi $lokasi): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'create',
            'nama_tabel' => 'lokasi',
            'record_id' => $lokasi->id,
            'deskripsi' => "Menambahkan lokasi baru: {$lokasi->nama_lokasi}",
            'perubahan_data' => $lokasi->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Lokasi "updated" event.
     */
    public function updated(Lokasi $lokasi): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'update',
            'nama_tabel' => 'lokasi',
            'record_id' => $lokasi->id,
            'deskripsi' => "Mengubah data lokasi: {$lokasi->nama_lokasi}",
            'perubahan_data' => [
                'before' => $lokasi->getOriginal(),
                'after' => $lokasi->getAttributes(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Lokasi "deleted" event.
     */
    public function deleted(Lokasi $lokasi): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'jenis_aktivitas' => 'delete',
            'nama_tabel' => 'lokasi',
            'record_id' => $lokasi->id,
            'deskripsi' => "Menghapus lokasi: {$lokasi->nama_lokasi}",
            'perubahan_data' => $lokasi->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Lokasi "restored" event.
     */
    public function restored(Lokasi $lokasi): void
    {
        //
    }

    /**
     * Handle the Lokasi "force deleted" event.
     */
    public function forceDeleted(Lokasi $lokasi): void
    {
        //
    }
}
