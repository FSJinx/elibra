<?php

namespace App\Permissions;

class CampusPermission
{
    public static function all()
    {
        return [
            ['module' => 'campus', 'action' => 'all', 'permission' => 'campus.all'],

            ['module' => 'campus', 'action' => 'create', 'permission' => 'campus.create'],

            ['module' => 'campus', 'action' => 'view', 'permission' => 'campus.view'],
            ['module' => 'campus', 'action' => 'viewAny', 'permission' => 'campus.viewAny'],

            ['module' => 'campus', 'action' => 'update', 'permission' => 'campus.update'],
            ['module' => 'campus', 'action' => 'updateAny', 'permission' => 'campus.updateAny'],

            ['module' => 'campus', 'action' => 'delete', 'permission' => 'campus.delete'],
            ['module' => 'campus', 'action' => 'deleteAny', 'permission' => 'campus.deleteAny'],
            
            ['module' => 'campus', 'action' => 'forceDelete', 'permission' => 'campus.forceDelete'],
            ['module' => 'campus', 'action' => 'forceDeleteAny', 'permission' => 'campus.forceDeleteAny'],
        ];
    }
}
