<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BooksPolicy
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
    public function view(User $user, Book $books): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isLibrarian();
        // return $user->hasPermission('book.create') || $user->isAdmin() || $user->isLibrarian();
        // return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $books): bool
    {
        return $user->hasPermission('book.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Book $books): bool
    {
        return $user->hasPermission('book.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Book $books): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Book $books): bool
    {
        return false;
    }
}
