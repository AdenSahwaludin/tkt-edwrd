<?php

namespace App\Policies;

use App\Models\TransaksiBarang;
use App\Models\User;

class TransaksiBarangPolicy
{
    /**
     * Semua role dapat melihat daftar transaksi barang.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Semua role dapat melihat detail transaksi barang.
     */
    public function view(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return true;
    }

    /**
     * Petugas Inventaris yang dapat membuat transaksi.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_transaksi_barangs');
    }

    /**
     * Petugas Inventaris yang dapat mengubah transaksi.
     */
    public function update(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->hasPermissionTo('edit_transaksi_barangs');
    }

    /**
     * Petugas Inventaris yang dapat menghapus transaksi.
     */
    public function delete(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->hasPermissionTo('delete_transaksi_barangs');
    }

    /**
     * Petugas Inventaris yang dapat menghapus multiple transaksi.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_transaksi_barangs');
    }

    /**
     * Petugas Inventaris yang dapat restore transaksi.
     */
    public function restore(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->hasPermissionTo('delete_transaksi_barangs');
    }

    /**
     * Petugas Inventaris yang dapat restore multiple transaksi.
     */
    public function restoreAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_transaksi_barangs');
    }

    /**
     * Petugas Inventaris yang dapat force delete transaksi.
     */
    public function forceDelete(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->hasPermissionTo('delete_transaksi_barangs');
    }

    /**
     * Petugas Inventaris yang dapat force delete multiple transaksi.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_transaksi_barangs');
    }

    /**
     * Kepala Sekolah yang dapat menyetujui transaksi masuk.
     */
    public function approve(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->hasPermissionTo('approve_transaksi_barangs');
    }
}
