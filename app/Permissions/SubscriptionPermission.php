<?php

namespace App\Permissions;

class SubscriptionPermission
{
    public static function all()
    {
        return [
            ['module' => 'subscription', 'action' => 'create', 'permission' => 'subscription.create'],

            ['module' => 'subscription', 'action' => 'view', 'permission' => 'subscription.view'],

            ['module' => 'subscription', 'action' => 'update', 'permission' => 'subscription.update'],

            ['module' => 'subscription', 'action' => 'delete', 'permission' => 'subscription.delete'],
            
            ['module' => 'subscription', 'action' => 'forceDelete', 'permission' => 'subscription.forceDelete'],
        ];
    }
}
