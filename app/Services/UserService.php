<?php

namespace App\Services;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserService
{
    //ENCAPSULATES
    private const ROLE_PERMISSIONS = [
        'admin' => [
            'branch.create',
            'branch.view',
            'branch.update',
            'branch.delete',
            'branch.forceDelete',
        ],
        'librarian' => [
            'book.create',
            'book.view',
            'book.update',
            'book.delete',

            'borrow.create',
            'borrow.view',
            'borrow.update',
        ],
        'patron' => [
            'user.update',
            'user.view'
        ],
    ];

    public static function assignRoleAndPermissions(User $user, string $role): void
    {

        $permissions = self::ROLE_PERMISSIONS[$role] ?? [];

        if (empty($permissions)) {
            return;
        }

        //Prevent injecting unknown role
        if(!array_key_exists($role, self::ROLE_PERMISSIONS)){
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Role',
                ])
            );
        }

        $permissionIds = Permission::whereIn(
            'permission',
            $permissions
        )->pluck('id');

        $user->permissions()->syncWithoutDetaching($permissionIds);
    }
    /**
     * syncWithoutDetaching => This will add the provided relationship and will keep
     *                         the existing record to AVOID DUPLICATION
     */

    public static function verifyCampus(User $authUser, array &$data): void
    {
        //Check kung admin
        if(! $authUser->isAdmin()){
            return;
        }

        if(is_null($authUser->campus_id)){
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => 'Admin account has no assigned campus.'
                ])
            );
        }

        $data['campus_id'] = $authUser->campus_id;

    }

}   