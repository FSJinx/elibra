<?php

namespace App\Permissions;

class AcquisitionLinePermission
{
    public static function all()
    {
        return [
            ['module' => 'acquisition.line', 'action' => 'create', 'permission' => 'acquisition.line.create'],

            ['module' => 'acquisition.line', 'action' => 'view', 'permission' => 'acquisition.line.view'],

            ['module' => 'acquisition.line', 'action' => 'update', 'permission' => 'acquisition.line.update'],

            ['module' => 'acquisition.line', 'action' => 'delete', 'permission' => 'acquisition.line.delete'],
            
            ['module' => 'acquisition.line', 'action' => 'forceDelete', 'permission' => 'acquisition.line.forceDelete'],
        ];
    }
}
