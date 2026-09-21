<?php

namespace App\Permissions;

class AccessionPermission
{
    public static function all()
    {
        return [
            ['module' => 'accession', 'action' => 'create', 'permission' => 'accession.create'],

            ['module' => 'accession', 'action' => 'view', 'permission' => 'accession.view'],

            ['module' => 'accession', 'action' => 'update', 'permission' => 'accession.update'],

            ['module' => 'accession', 'action' => 'delete', 'permission' => 'accession.delete'],
            
            ['module' => 'accession', 'action' => 'forceDelete', 'permission' => 'accession.forceDelete'],
        ];
    }
}
