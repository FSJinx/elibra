<?php

namespace App\Permissions;

class BookPermission
{
    public static function all()
    {
        return [
            ['module' => 'book', 'action' => 'create', 'permission' => 'book.create'],

            ['module' => 'book', 'action' => 'view', 'permission' => 'book.view'],

            ['module' => 'book', 'action' => 'update', 'permission' => 'book.update'],

            ['module' => 'book', 'action' => 'delete', 'permission' => 'book.delete'],
            
            ['module' => 'book', 'action' => 'forceDelete', 'permission' => 'book.forceDelete'],
        ];
    }
}
