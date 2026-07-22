<?php

namespace App\Services;

use App\Models\User;
use App\Models\Permission;
use App\Models\UserPermission;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService
{
    //ENCAPSULATES
    private const ADMIN_PERMISSIONS = [
        'branch.create',
        'branch.view',
        'branch.update',
        'branch.delete',
        'branch.forceDelete',
    ];

    public static function adminPermissions(User $user): void
    {

        $permissionIds = Permission::whereIn(
            'permission',
            self::ADMIN_PERMISSIONS
        )->pluck('id');
        //instead of get, use pluck to forcibly get all the id's para wala ng looping
        //this is much better than looping all the permissions

        $user->permissions()->syncWithoutDetaching($permissionIds);
        
    }
    /**
     * syncWithoutDetaching => This will add the provided relationship and will keep
     *                         the existing record to AVOID DUPLICATION
     */
}   