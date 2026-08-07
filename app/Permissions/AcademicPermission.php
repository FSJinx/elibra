<?php

namespace App\Permissions;

class AcademicPermission
{
    public static function all()
    {
        return [
            ['module' => 'academic', 'action' => 'create', 'permission' => 'academic.create'],

            ['module' => 'academic', 'action' => 'view', 'permission' => 'academic.view'],

            ['module' => 'academic', 'action' => 'update', 'permission' => 'academic.update'],

            ['module' => 'academic', 'action' => 'delete', 'permission' => 'academic.delete'],
            
            ['module' => 'academic', 'action' => 'forceDelete', 'permission' => 'academic.forceDelete'],
        ];
    }
}
