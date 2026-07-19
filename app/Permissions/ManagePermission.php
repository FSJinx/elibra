<?php

namespace App\Permissions;

class ManagePermission
{
    public static function all()
    {
        return [
            ['module' => 'manage', 'action' => 'create', 'permission' => 'manage.create'],

            ['module' => 'manage', 'action' => 'view', 'permission' => 'manage.view'],

            ['module' => 'manage', 'action' => 'update', 'permission' => 'manage.update'],

            ['module' => 'manage', 'action' => 'delete', 'permission' => 'manage.delete'],
            
            ['module' => 'manage', 'action' => 'forceDelete', 'permission' => 'manage.forceDelete'],
        ];
    }
}
