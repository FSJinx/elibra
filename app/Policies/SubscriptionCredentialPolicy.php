<?php

namespace App\Policies;

use App\Models\SubscriptionCredential;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionCredentialPolicy
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
    public function view(User $user, SubscriptionCredential $subscriptionCredential): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin()
            || ($user->isLibrarian() && $user->hasPermission('subscription.credential.create'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubscriptionCredential $subscriptionCredential): bool
    {
        return $user->isAdmin()
            || ($user->isLibrarian() && $user->hasPermission('subscription.credential.update'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubscriptionCredential $subscriptionCredential): bool
    {
        return $user->isAdmin()
            || ($user->isLibrarian() && $user->hasPermission('subscription.credential.delete'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubscriptionCredential $subscriptionCredential): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubscriptionCredential $subscriptionCredential): bool
    {
        return false;
    }
}
