<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\BranchSection;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BranchSectionPolicy
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
    public function view(User $user, BranchSection $branchSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Branch $branch): bool // i will pass the campus_id here, para dito ma validate
    {
        if($user->isAdmin()){
            return true;
        }

        if (! $user->hasPrimaryRole('library admin')) {
            return false;
        }

        return $branch->campus_id === $user->librarian->branch->campus_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BranchSection $branchSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BranchSection $branchSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BranchSection $branchSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BranchSection $branchSection): bool
    {
        return false;
    }
}
