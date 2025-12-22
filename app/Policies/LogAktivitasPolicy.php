<?php

namespace App\Policies;

use App\Models\LogAktivitas;
use App\Models\User;

class LogAktivitasPolicy
{
    /**
     * Semua role dengan permission dapat melihat log aktivitas.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_log_aktivitas');
    }

    /**
     * Semua role dengan permission dapat melihat detail log aktivitas.
     */
    public function view(User $user, LogAktivitas $logAktivitas): bool
    {
        return $user->hasPermissionTo('view_log_aktivitas');
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
     * Hanya Admin dengan permission yang dapat menghapus log aktivitas.
     */
    public function delete(User $user, LogAktivitas $logAktivitas): bool
    {
        return $user->hasPermissionTo('delete_log_aktivitas');
    }

    /**
     * Hanya Admin dengan permission yang dapat menghapus multiple log aktivitas.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_log_aktivitas');
    }

    /**
     * Tidak ada yang dapat restore log.
     */
    public function restore(User $user, LogAktivitas $logAktivitas): bool
    {
        return false;
    }

    /**
     * Hanya Admin dengan permission yang dapat force delete log.
     */
    public function forceDelete(User $user, LogAktivitas $logAktivitas): bool
    {
        return $user->hasPermissionTo('delete_log_aktivitas');
    }

    /**
     * Hanya Admin dengan permission yang dapat restore any log.
     */
    public function restoreAny(User $user): bool
    {
        return false;
    }

    /**
     * Hanya Admin dengan permission yang dapat force delete any log.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_log_aktivitas');
    }
}
