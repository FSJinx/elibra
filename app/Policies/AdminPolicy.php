<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user): bool
    {
       return $user->isSuperAdmin();
    }
}
