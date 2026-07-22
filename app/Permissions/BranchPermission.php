<?php

namespace App\Permissions;

class BranchPermission
{
    public static function all()
    {
        return [
            ['module' => 'branch', 'action' => 'create', 'permission' => 'branch.create'],

            ['module' => 'branch', 'action' => 'view', 'permission' => 'branch.view'],

            ['module' => 'branch', 'action' => 'update', 'permission' => 'branch.update'],

            ['module' => 'branch', 'action' => 'delete', 'permission' => 'branch.delete'],
            
            ['module' => 'branch', 'action' => 'forceDelete', 'permission' => 'branch.forceDelete'],
        ];
    }
}
