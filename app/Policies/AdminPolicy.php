<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserPermission;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user): bool
    {
       return $user->isSuperAdmin();
    }

    public function update(User $user, User $admin): bool
    {
        if(! $user->isSuperAdmin()) {
            return false;
        }

        return $user->id !== $admin->id;
    }

    public function delete(User $user, User $admin): bool
    {
        if(! $user->isSuperAdmin()) {
            return false;
        }

        return $user->id !== $admin->id;
    }
}
