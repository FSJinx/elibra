<?php

namespace App\Permissions;

class UserPermission
{
    public static function all()
    {
        return [
            ['module' => 'user', 'action' => 'create', 'permission' => 'user.create'],

            ['module' => 'user', 'action' => 'view', 'permission' => 'user.view'],

            ['module' => 'user', 'action' => 'update', 'permission' => 'user.update'],

            ['module' => 'user', 'action' => 'delete', 'permission' => 'user.delete'],
            
            ['module' => 'user', 'action' => 'forceDelete', 'permission' => 'user.forceDelete'],
        ];
    }
}
