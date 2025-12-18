<?php

namespace App\Policies;

use App\Models\Barang;
use App\Models\User;

class BarangPolicy
{
    /**
     * Semua role dapat melihat daftar barang.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Semua role dapat melihat detail barang.
     */
    public function view(User $user, Barang $barang): bool
    {
        return true;
    }

    /**
     * Hanya Admin dan Petugas Inventaris yang dapat menambah barang.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasInventaris();
    }

    /**
     * Hanya Admin dan Petugas Inventaris yang dapat mengubah barang.
     */
    public function update(User $user, Barang $barang): bool
    {
        return $user->isAdmin() || $user->isPetugasInventaris();
    }

    /**
     * Hanya Admin yang dapat menghapus barang.
     */
    public function delete(User $user, Barang $barang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat menghapus multiple barang.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat restore barang.
     */
    public function restore(User $user, Barang $barang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat restore multiple barang.
     */
    public function restoreAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat force delete barang.
     */
    public function forceDelete(User $user, Barang $barang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat force delete multiple barang.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
