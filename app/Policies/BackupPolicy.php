<?php

namespace App\Policies;

use App\Models\User;

class BackupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('backup_system');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('backup_system');
    }

    public function restore(User $user): bool
    {
        return $user->hasPermissionTo('restore_system');
    }

    public function download(User $user): bool
    {
        return $user->hasPermissionTo('backup_system');
    }
}
