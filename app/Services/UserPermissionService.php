<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserPermissionService
{
    /**
     * Permissions that should never be assigned
     * to librarians or patrons.
     */
    private const MANAGEMENT_PERMISSIONS = [
        'manage.create',
        'manage.update',
        'manage.delete',
        'manage.restore',
        'manage.forceDelete',

        'academic.create',
        'academic.update',
    ];
    /**
     * Reusable response 
     */
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
     * View permission management
     * [Super Admin] -> can view all
     * [Admin] -> can view based on campus_id
     */

    public static function view(User $actor)
    {
        //Eager Loading, Kung super fethc all
        if($actor->isSuperAdmin()){
            return UserPermission::with([
                'user',
                'permission',
            ])->get();
        }

        //Di pwedeng mag fetch ang librarian and patron
        if(in_array($actor->role, ['librarian', 'patron'], true)){
            self::forbidden('You are not authorized to perform this action');
        }

        // fetch lang yung librarian based sa users campus
        $userPermissions = UserPermission::whereHas('user', function ($query) use ($actor){
            $query->where('campus_id', $actor->campus_id)
                  ->whereIn('role', [
                        'librarian', 
                        'patron'
                    ]);
        })->with(['user', 'permission'])->get(['user_id', 'permission_id']);

        //if no record
        if($userPermissions === null){
            self::forbidden('Empty Record!');
        }

        return $userPermissions;
    }

    /**
     * Assign a permission to a user.
     *
     * @param User $actor The authenticated user performing the assignment.
     * @param User $targetUser The user who will receive the permission.
     * @param Permission $permission The permission to assign.
     */
    public static function assign(
        User $actor,
        User $targetUser,
        Permission $permission
    ): UserPermission {
        
        self::authorizeAssignment(
            $actor,
            $targetUser,
            $permission
        );

        return UserPermission::firstOrCreate([
            'user_id' => $targetUser->id,
            'permission_id' => $permission->id,
        ]);
    }


    /**
     * Update an existing permission assignment.
     */
    public static function update(
        User $actor,
        UserPermission $userPermission,
        Permission $permission
    ): UserPermission {

        $targetUser = $userPermission->user;

        self::authorizeManagement($actor, $targetUser);

        if (
            ! $actor->isSuperAdmin() &&
            ! $actor->hasPermission('manage.update')
        ) {
            self::forbidden(
                'You are not allowed to update permissions.'
            );
        }

        if ($targetUser->hasPermission($permission->permission)) {
            self::forbidden(
                'The user already has this permission.'
            );
        }

        self::validateRestrictedPermissions(
            $targetUser,
            $permission
        );

        $userPermission->update([
            'permission_id' => $permission->id,
        ]);

        return $userPermission;
    }

    /**
     * Delete a permission assignment.
     */
    public static function delete(
        User $actor,
        UserPermission $userPermission
    ): void {

        $targetUser = $userPermission->user;

        self::authorizeManagement($actor, $targetUser);

        if (
            ! $actor->isSuperAdmin() &&
            ! $actor->hasPermission('manage.delete')
        ) {
            self::forbidden(
                'You are not allowed to delete permissions.'
            );
        }

        $userPermission->delete();
    }


    /**
     * Checker kung yung permission ay pwede i-assign
     */
    private static function authorizeAssignment(
        User $actor,
        User $targetUser,
        Permission $permission
    ): void {

        self::authorizeManagement($actor, $targetUser);

        if (
            ! $actor->isSuperAdmin() &&
            ! $actor->hasPermission('manage.create')
        ) {
            self::forbidden(
                'You are not allowed to assign permissions.'
            );
        }

        if ($targetUser->hasPermission($permission->permission)) {
            self::forbidden(
                'The user already has this permission.'
            );
        }

        self::validateRestrictedPermissions(
            $targetUser,
            $permission
        );
    }

     /**
     * Shared authorization rules for managing another user's permissions.
     */
    private static function authorizeManagement(
        User $actor,
        User $targetUser
    ): void {

        if ($targetUser->isSuperAdmin()) {
            self::forbidden(
                'You cannot manage the Super Admin account.'
            );
        }

        if ($actor->isSuperAdmin()) {
            return;
        }

        if ($actor->campus_id === null || $targetUser->campus_id === null) {
            self::forbidden(
                'Both users must belong to a campus.'
            );
        }

        if ($actor->campus_id !== $targetUser->campus_id) {
            self::forbidden(
                'You cannot manage users from another campus.'
            );
        }

        if (! in_array($targetUser->role, ['librarian', 'patron'], true)) {
            self::forbidden(
                'You cannot manage this user.'
            );
        }
    }

    /**
     * Prevent management permissions from being assigned
     * to librarians and patrons.
     */
    private static function validateRestrictedPermissions(
        User $targetUser,
        Permission $permission
    ): void {

        if (
            in_array(
                $permission->permission,
                self::MANAGEMENT_PERMISSIONS,
                true
            ) &&
            in_array(
                $targetUser->role,
                ['librarian', 'patron'],
                true
            )
        ) {
            self::forbidden(
                'Librarians and patrons cannot receive management permissions.'
            );
        }
    }


}