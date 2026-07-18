<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserPermissionService
{
    public static function initializePermissions(User $user): void 
    {
        //use early return if not admin
        if ($user->role !== 'admin') {
            return;
        }

        $permissions = [
            'user.all',
            'campus.all',
            'branch.all',
            // 'manage.all',
        ];

        foreach ($permissions as $permission) {
            $permit = Permission::where('permission', $permission)->first(); // Checks kung merong permissio
            
            //para ma avoid yung null data [permission] if ever
            if($permit) {
                UserPermission::firstOrCreate([ //firstOrCreate to avoid duplication of insertion
                    'user_id' => $user->id,
                    'permission_id' => $permit->id,
                ]);
            }
        }
    }

    private static function forbidden(string $message): never
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => $message,
            ], 403)
        );
    }
    /**
     * Check if the actor can assign the permission.
     */
    private static function authorizeAssignment(
        User $actor,
        User $targetUser,
        Permission $permission
    ): void {

        if ($targetUser->isSuperAdmin()) {
            self::forbidden('You cannot manage the Super Admin account.');
        }

        if ($actor->isSuperAdmin()) {
            return;
        }
        // dd($permission);
        if ($targetUser->hasPermission($permission->permission)) {
            self::forbidden('The user already has this permission.');
        }

        if (! $actor->hasPermission('manage.create')) {
            self::forbidden('You are not allowed to assign permissions.');
        }

        if ($actor->campus_id === null || $targetUser->campus_id === null) {
            self::forbidden('Both users must belong to a campus.');
        }

        if ($actor->campus_id !== $targetUser->campus_id) {
            self::forbidden('You cannot manage users from another campus.');
        }

        $restrictedPermissions = [
            'manage.create',
            'manage.update',
            'manage.delete',
            'manage.restore',
            'manage.forceDelete',
        ];

        if (
            in_array($permission->permission, $restrictedPermissions, true) &&
            in_array($targetUser->role, ['librarian', 'patron'], true)
        ) {
            self::forbidden(
                'Librarians and patrons cannot receive management permissions.'
            );
        }
    }

    /**
     * Assign a permission to a user.
     * 
     *  * @param User $actor The authenticated user performing the assignment.
        * @param User $targetUser The user who will receive the permission.
        * @param Permission $permission The permission to be assigned.
     *  *
     * 
     */
    public static function assign(
        User $actor,
        User $targetUser,
        Permission $permission
    ): UserPermission {

        // allowed ba yung acting user na ma assign?
        self::authorizeAssignment($actor, $targetUser, $permission);

         // Create the permission assignment only if it doesn't already exist kay kung meron, na edi wala 
        return UserPermission::firstOrCreate([
            'user_id' => $targetUser->id,
            'permission_id' => $permission->id,
        ]);
    }
}