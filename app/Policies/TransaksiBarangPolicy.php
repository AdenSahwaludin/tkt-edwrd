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
     * Hanya Admin dan Petugas Inventaris yang dapat membuat transaksi.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isPetugasInventaris();
    }

    /**
     * Hanya Admin yang dapat mengubah transaksi (untuk koreksi data).
     */
    public function update(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat menghapus transaksi.
     */
    public function delete(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat menghapus multiple transaksi.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat restore transaksi.
     */
    public function restore(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang dapat force delete transaksi.
     */
    public function forceDelete(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya user dengan permission approve_transaksi_barangs yang dapat menyetujui transaksi.
     * Biasanya Kepala Sekolah dan Admin.
     */
    public function approve(User $user, TransaksiBarang $transaksiBarang): bool
    {
        return $user->hasPermissionTo('approve_transaksi_barangs');
    }
}
