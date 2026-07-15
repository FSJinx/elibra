<?php

namespace App\Permissions;

class UserPermission
{
    public static function all()
    {
        return [
            ['module' => 'user', 'action' => 'all', 'permission' => 'user.all'],

            ['module' => 'user', 'action' => 'create', 'permission' => 'user.create'],

            ['module' => 'user', 'action' => 'view', 'permission' => 'user.view'],
            ['module' => 'user', 'action' => 'viewAny', 'permission' => 'user.viewAny'],

            ['module' => 'user', 'action' => 'update', 'permission' => 'user.update'],
            ['module' => 'user', 'action' => 'updateAny', 'permission' => 'user.updateAny'],

            ['module' => 'user', 'action' => 'delete', 'permission' => 'user.delete'],
            ['module' => 'user', 'action' => 'deleteAny', 'permission' => 'user.deleteAny'],

            ['module' => 'user', 'action' => 'restore', 'permission' => 'user.restore'],
            
            ['module' => 'user', 'action' => 'forceDelete', 'permission' => 'user.forceDelete'],
            ['module' => 'user', 'action' => 'forceDeleteAny', 'permission' => 'user.forceDeleteAny'],
        ];
    }
}
