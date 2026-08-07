<?php

namespace App\Policies;

use App\Models\Academic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcademicPolicy
{
    public function isAuthorized(User $user): bool
    {
        return in_array($user->role, [$user->isAdmin(), $user->isLibrarian()]);
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Academic $academic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('academic.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Academic $academic): bool
    {
        return $user->hasPermission('academic.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Academic $academic): bool
    {
        return $user->hasPermission('academic.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Academic $academic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Academic $academic): bool
    {
        return false;
    }
}
