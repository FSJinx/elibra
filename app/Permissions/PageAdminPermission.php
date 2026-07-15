<?php

namespace App\Permissions;

class PageAdminPermission
{
    public static function all()
    {
        return [
            ['module' => 'page.admin', 'action' => 'all', 'permission' => 'page.admin.all'],
        ];
    }
}
