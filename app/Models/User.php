<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

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

    public function section()
    {
        return $this->belongsTo($this->librarian(), 'section_id');
    }

    public function patron()
    {
        return $this->hasOne(Patron::class, 'user_id');
    }

    public function program()
    {
        return $this->belongsTo($this->patron(), 'program_id');
    }

    // Check if user has a primary role
    // Tapno awamen ti primary role ti user, mabalin mo nga usaren daytoy a method
    public function hasPrimaryRole(string $role): bool
    {
        return strtolower($this->librarian?->primary_role?->name ?? '') === strtolower($role);
    }

}
