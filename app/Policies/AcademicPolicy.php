<?php

namespace App\Policies;

use App\Models\Academic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcademicPolicy
{
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
        // Check if user is a librarian
        if (!$user->librarian) {
            return false;
        }

        $librarian = $user->librarian;

        // Check if librarian has a primary role
        if (!$librarian->primary_role) {
            return false;
        }

        // Check if primary role is 'Academics'
        if (strtolower($librarian->primary_role->name) !== 'academics') {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Academic $academic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Academic $academic): bool
    {
        return false;
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
