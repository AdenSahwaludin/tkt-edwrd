<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    /**
     * Semua role dapat melihat daftar laporan.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Semua role dapat melihat detail laporan.
     */
    public function view(User $user, Laporan $laporan): bool
    {
        return true;
    }

    /**
     * Semua role dapat membuat laporan (generate PDF).
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Tidak ada yang dapat mengubah laporan yang sudah dibuat.
     */
    public function update(User $user, Laporan $laporan): bool
    {
        return false;
    }

    /**
     * Hanya Admin yang dapat menghapus laporan.
     */
    public function delete(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat menghapus multiple laporan.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat restore laporan.
     */
    public function restore(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat force delete laporan.
     */
    public function forceDelete(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin();
    }
}
