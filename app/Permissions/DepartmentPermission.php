<?php

namespace App\Permissions;

class DepartmentPermission
{
    public static function all()
    {
        return [
            ['module' => 'department', 'action' => 'create', 'permission' => 'department.create'],

            ['module' => 'department', 'action' => 'view', 'permission' => 'department.view'],

            ['module' => 'department', 'action' => 'update', 'permission' => 'department.update'],

            ['module' => 'department', 'action' => 'delete', 'permission' => 'department.delete'],
            
            ['module' => 'department', 'action' => 'forceDelete', 'permission' => 'department.forceDelete'],
        ];
    }
}
