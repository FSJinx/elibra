<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Branch;
use App\Models\User;

class BranchPolicy
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
    public function view(User $user, Branch $branch): bool
    {
        // return $user->hasPrimaryRole('library admin');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Branch $branch): bool
    {
        if(!$user->isAdmin()){
            return false;
        }

        return $user->campus_id === $branch->campus_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Branch $branch): bool
    {
        // Librarians can only update branches in their own campus
        if(!$user->isAdmin()){
            return false;
        }
        
        return $user->campus_id === $branch->campus_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Branch $branch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Branch $branch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Branch $branch): bool
    {
        return false;
    }
}
