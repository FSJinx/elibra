<?php

namespace App\Permissions;

class BranchPermission
{
    public static function all()
    {
        return [
            ['module' => 'branch', 'action' => 'all', 'permission' => 'branch.all'],

            ['module' => 'branch', 'action' => 'create', 'permission' => 'branch.create'],

            ['module' => 'branch', 'action' => 'view', 'permission' => 'branch.view'],
            ['module' => 'branch', 'action' => 'viewAny', 'permission' => 'branch.viewAny'],

            ['module' => 'branch', 'action' => 'update', 'permission' => 'branch.update'],
            ['module' => 'branch', 'action' => 'updateAny', 'permission' => 'branch.updateAny'],

            ['module' => 'branch', 'action' => 'delete', 'permission' => 'branch.delete'],
            ['module' => 'branch', 'action' => 'deleteAny', 'permission' => 'branch.deleteAny'],
            
            ['module' => 'branch', 'action' => 'forceDelete', 'permission' => 'branch.forceDelete'],
            ['module' => 'branch', 'action' => 'forceDeleteAny', 'permission' => 'branch.forceDeleteAny'],
        ];
    }
}
