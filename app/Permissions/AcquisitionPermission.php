<?php

namespace App\Permissions;

class AcquisitionPermission
{
    public static function all()
    {
        return [
            ['module' => 'acquisition', 'action' => 'create', 'permission' => 'acquisition.create'],

            ['module' => 'acquisition', 'action' => 'view', 'permission' => 'acquisition.view'],

            ['module' => 'acquisition', 'action' => 'update', 'permission' => 'acquisition.update'],

            ['module' => 'acquisition', 'action' => 'delete', 'permission' => 'acquisition.delete'],
            
            ['module' => 'acquisition', 'action' => 'forceDelete', 'permission' => 'acquisition.forceDelete'],
        ];
    }
}
