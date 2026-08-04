<?php

namespace App\Permissions;

class BranchSectionPermission
{
    public static function all()
    {
        return [
            ['module' => 'branch.section', 'action' => 'create', 'permission' => 'branch.section.create'],

            ['module' => 'branch.section', 'action' => 'view', 'permission' => 'branch.section.view'],

            ['module' => 'branch.section', 'action' => 'update', 'permission' => 'branch.section.update'],

            ['module' => 'branch.section', 'action' => 'delete', 'permission' => 'branch.section.delete'],
            
            ['module' => 'branch.section', 'action' => 'forceDelete', 'permission' => 'branch.section.forceDelete'],
        ];
    }
}
