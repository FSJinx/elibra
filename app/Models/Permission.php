<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory;

    protected $fillable = [
        'permission',
        'module',
        'action',
    ];

    public function users(){

        return $this->belongsToMany(
            User::class,
            'user_permissions',
            'permission_id',
            'user_id'
        );
        
    }
}
