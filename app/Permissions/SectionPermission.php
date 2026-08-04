<?php

namespace App\Permissions;

class SectionPermission
{
    public static function all()
    {
        return [
            ['module' => 'section', 'action' => 'create', 'permission' => 'section.create'],

            ['module' => 'section', 'action' => 'view', 'permission' => 'section.view'],

            ['module' => 'section', 'action' => 'update', 'permission' => 'section.update'],

            ['module' => 'section', 'action' => 'delete', 'permission' => 'section.delete'],
            
            ['module' => 'section', 'action' => 'forceDelete', 'permission' => 'section.forceDelete'],
        ];
    }
}
