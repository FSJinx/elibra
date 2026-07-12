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
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Branch $branch): bool
    {
        // Admins can update any branch
        if($user->isAdmin()) {
            return true;
        }

        // Librarians can only update branches in their own campus
        if(!$user->hasPrimaryRole('library admin')){
            return false;
        }

        // Get the campus ID of the librarian's branch
        $userCampusId = $user->librarian->branch->campus_id;

        // If the user doesn't have a branch or the branch doesn't belong to a campus, deny access
        if (! $user->librarian || ! $user->librarian->branch) {
            return false;
        }

        // Check if the branch being updated belongs to the same campus as the librarian's branch
        return $branch->campus_id === $userCampusId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Branch $branch): bool
    {
        return $user->isAdmin();
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
