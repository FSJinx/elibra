<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes;

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

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

    public function librarian()
    {
        return $this->hasOne(Librarian::class, 'user_id');
    }

    public function patron()
    {
        return $this->hasOne(Patron::class, 'user_id');
    }
}
