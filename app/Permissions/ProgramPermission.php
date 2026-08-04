<?php

namespace App\Permissions;

class ProgramPermission
{
    public static function all()
    {
        return [
            ['module' => 'program', 'action' => 'create', 'permission' => 'program.create'],

            ['module' => 'program', 'action' => 'view', 'permission' => 'program.view'],

            ['module' => 'program', 'action' => 'update', 'permission' => 'program.update'],

            ['module' => 'program', 'action' => 'delete', 'permission' => 'program.delete'],
            
            ['module' => 'program', 'action' => 'forceDelete', 'permission' => 'program.forceDelete'],
        ];
    }
}
