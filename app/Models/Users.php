<?php

namespace App\Models;

use Database\Factories\UsersFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    /** @use HasFactory<UsersFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_initial',
        'sex',
        'birthdate',
        'contact_number',
        'email',
        'email_verified_at',
        'role',
        'code',
        'status',
        'login_attempts',
        'username',
        'password',
        'profile_picture_id',
    ];
}
