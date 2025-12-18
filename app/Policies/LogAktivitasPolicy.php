<?php

namespace App\Policies;

use App\Models\LogAktivitas;
use App\Models\User;

class LogAktivitasPolicy
{
    /**
     * Semua role dapat melihat log aktivitas.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Semua role dapat melihat detail log aktivitas.
     */
    public function view(User $user, LogAktivitas $logAktivitas): bool
    {
        return true;
    }

    /**
     * Tidak ada yang dapat membuat log manual (dibuat otomatis oleh observers).
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Tidak ada yang dapat mengubah log aktivitas.
     */
    public function update(User $user, LogAktivitas $logAktivitas): bool
    {
        return false;
    }

    /**
     * Hanya Admin yang dapat menghapus log aktivitas (untuk cleanup).
     */
    public function delete(User $user, LogAktivitas $logAktivitas): bool
    {
        return $user->isAdmin();
    }
    /**
     * Hanya Admin yang dapat menghapus multiple log aktivitas.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
    /**
     * Tidak ada yang dapat restore log.
     */
    public function restore(User $user, LogAktivitas $logAktivitas): bool
    {
        return false;
    }

    /**
     * Hanya Admin yang dapat force delete log.
     */
    public function forceDelete(User $user, LogAktivitas $logAktivitas): bool
    {
        return $user->isAdmin();
    }
}
