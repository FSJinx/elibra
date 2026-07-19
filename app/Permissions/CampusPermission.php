<?php

namespace App\Permissions;

class CampusPermission
{
    public static function all()
    {
        return [

            ['module' => 'campus', 'action' => 'create', 'permission' => 'campus.create'],

            ['module' => 'campus', 'action' => 'view', 'permission' => 'campus.view'],

            ['module' => 'campus', 'action' => 'update', 'permission' => 'campus.update'],

            ['module' => 'campus', 'action' => 'delete', 'permission' => 'campus.delete'],
            
            ['module' => 'campus', 'action' => 'forceDelete', 'permission' => 'campus.forceDelete'],
        ];
    }
}
