<?php

namespace App\Permissions;

class PageLibraryPermission
{
    public static function all()
    {
        return [
            ['module' => 'page.library', 'action' => 'all', 'permission' => 'page.library.all'],
        ];
    }
}
