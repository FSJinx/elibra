<?php

namespace App\Permissions;

class SubscriptionCredentialPermission
{
    public static function all()
    {
        return [
            ['module' => 'subscription.credential', 'action' => 'create', 'permission' => 'subscription.credential.create'],

            ['module' => 'subscription.credential', 'action' => 'view', 'permission' => 'subscription.credential.view'],

            ['module' => 'subscription.credential', 'action' => 'update', 'permission' => 'subscription.credential.update'],

            ['module' => 'subscription.credential', 'action' => 'delete', 'permission' => 'subscription.credential.delete'],
            
            ['module' => 'subscription.credential', 'action' => 'forceDelete', 'permission' => 'subscription.credential.forceDelete'],
        ];
    }
}
