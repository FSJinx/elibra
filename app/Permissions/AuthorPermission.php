<?php

namespace App\Permissions;

class AuthorPermission
{
    public static function all()
    {
        return [
            ['module' => 'author', 'action' => 'create', 'permission' => 'author.create'],

            ['module' => 'author', 'action' => 'view', 'permission' => 'author.view'],

            ['module' => 'author', 'action' => 'update', 'permission' => 'author.update'],

            ['module' => 'author', 'action' => 'delete', 'permission' => 'author.delete'],
            
            ['module' => 'author', 'action' => 'forceDelete', 'permission' => 'author.forceDelete'],
        ];
    }
}
