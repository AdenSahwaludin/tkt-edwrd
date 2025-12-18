<?php

namespace App\Policies;

use App\Models\Kategori;
use App\Models\User;

class KategoriPolicy
{
    /**
     * Semua role dapat melihat daftar kategori.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Semua role dapat melihat detail kategori.
     */
    public function view(User $user, Kategori $kategori): bool
    {
        return true;
    }

    /**
     * Hanya Admin dan Petugas Inventaris yang dapat menambah kategori.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasInventaris();
    }

    /**
     * Hanya Admin dan Petugas Inventaris yang dapat mengubah kategori.
     */
    public function update(User $user, Kategori $kategori): bool
    {
        return $user->isAdmin() || $user->isPetugasInventaris();
    }

    /**
     * Hanya Admin yang dapat menghapus kategori.
     */
    public function delete(User $user, Kategori $kategori): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat menghapus multiple kategori.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat restore kategori.
     */
    public function restore(User $user, Kategori $kategori): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat restore multiple kategori.
     */
    public function restoreAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat force delete kategori.
     */
    public function forceDelete(User $user, Kategori $kategori): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat force delete multiple kategori.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
