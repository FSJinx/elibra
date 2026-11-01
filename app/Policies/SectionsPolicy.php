<?php

namespace App\Policies;

use App\Models\Library;
use App\Models\Sections;
use App\Models\User;

class SectionsPolicy
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
    public function view(User $user, Sections $sections): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin()
            || $user->isAdmin()
            || ($user->isLibrarian() && $user->librarian?->library_id !== null);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sections $sections): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin()
            && Library::query()
                ->whereKey($sections->library_id)
                ->where('campus_id', $user->campus_id)
                ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sections $sections): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin()
            && Library::query()
                ->whereKey($sections->library_id)
                ->where('campus_id', $user->campus_id)
                ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sections $sections): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sections $sections): bool
    {
        return false;
    }
}
