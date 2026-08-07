<?php

namespace App\Permissions;

class SerialPermission
{
    public static function all()
    {
        return [
            ['module' => 'serial', 'action' => 'create', 'permission' => 'serial.create'],

            ['module' => 'serial', 'action' => 'view', 'permission' => 'serial.view'],

            ['module' => 'serial', 'action' => 'update', 'permission' => 'serial.update'],

            ['module' => 'serial', 'action' => 'delete', 'permission' => 'serial.delete'],
            
            ['module' => 'serial', 'action' => 'forceDelete', 'permission' => 'serial.forceDelete'],
        ];
    }
}
