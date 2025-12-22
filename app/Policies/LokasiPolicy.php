<?php

namespace App\Policies;

use App\Models\Lokasi;
use App\Models\User;

class LokasiPolicy
{
    /**
     * Semua role dapat melihat daftar lokasi.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Semua role dapat melihat detail lokasi.
     */
    public function view(User $user, Lokasi $lokasi): bool
    {
        return true;
    }

    /**
     * Hanya Admin yang dapat membuat lokasi.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_lokasis');
    }

    /**
     * Hanya Admin yang dapat mengubah lokasi.
     */
    public function update(User $user, Lokasi $lokasi): bool
    {
        return $user->hasPermissionTo('edit_lokasis');
    }

    /**
     * Hanya Admin yang dapat menghapus lokasi.
     */
    public function delete(User $user, Lokasi $lokasi): bool
    {
        return $user->hasPermissionTo('delete_lokasis');
    }

    /**
     * Hanya Admin yang dapat menghapus multiple lokasi.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_lokasis');
    }

    /**
     * Hanya Admin yang dapat restore lokasi.
     */
    public function restore(User $user, Lokasi $lokasi): bool
    {
        return $user->hasPermissionTo('delete_lokasis');
    }

    /**
     * Hanya Admin yang dapat restore multiple lokasi.
     */
    public function restoreAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_lokasis');
    }

    /**
     * Hanya Admin yang dapat force delete lokasi.
     */
    public function forceDelete(User $user, Lokasi $lokasi): bool
    {
        return $user->hasPermissionTo('delete_lokasis');
    }

    /**
     * Hanya Admin yang dapat force delete multiple lokasi.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete_lokasis');
    }

    /**
     * Hanya Admin yang dapat force delete multiple lokasi.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
