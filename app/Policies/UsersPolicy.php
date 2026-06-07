<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Users;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;

class UsersPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(AuthUser $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(AuthUser $user, Users $users): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(AuthUser $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(AuthUser $user, Users $users): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(AuthUser $user, Users $users): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(AuthUser $user, Users $users): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(AuthUser $user, Users $users): bool
    {
        return false;
    }
}
