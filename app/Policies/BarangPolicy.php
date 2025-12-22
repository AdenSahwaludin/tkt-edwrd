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
     * Petugas Inventaris yang dapat menambah barang.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_barangs');
    }

    /**
     * Petugas Inventaris yang dapat mengubah barang.
     */
    public function update(User $user, Barang $barang): bool
    {
        return $user->hasPermissionTo('edit_barangs');
    }

    /**
     * Petugas Inventaris yang dapat menghapus barang.
     */
    public function delete(User $user, Barang $barang): bool
    {
        return $user->hasPermissionTo('delete_barangs');
    }

    /**
     * Petugas Inventaris yang dapat menghapus multiple barang.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_barangs');
    }

    /**
     * Petugas Inventaris yang dapat restore barang.
     */
    public function restore(User $user, Barang $barang): bool
    {
        return $user->hasPermissionTo('delete_barangs');
    }

    /**
     * Petugas Inventaris yang dapat restore multiple barang.
     */
    public function restoreAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_barangs');
    }

    /**
     * Petugas Inventaris yang dapat force delete barang.
     */
    public function forceDelete(User $user, Barang $barang): bool
    {
        return $user->hasPermissionTo('delete_barangs');
    }

    /**
     * Petugas Inventaris yang dapat force delete multiple barang.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_barangs');
    }
}
