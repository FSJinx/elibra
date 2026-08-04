<?php

namespace App\Permissions;

class MediaPermission
{
    public static function all()
    {
        return [
            ['module' => 'media', 'action' => 'create', 'permission' => 'media.create'],

            ['module' => 'media', 'action' => 'view', 'permission' => 'media.view'],

            ['module' => 'media', 'action' => 'update', 'permission' => 'media.update'],

            ['module' => 'media', 'action' => 'delete', 'permission' => 'media.delete'],
            
            ['module' => 'media', 'action' => 'forceDelete', 'permission' => 'media.forceDelete'],
        ];
    }
}
