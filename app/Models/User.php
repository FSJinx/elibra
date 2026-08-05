<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($user){
            $user->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $fillable = ['last_name', 'first_name', 'middle_initial', 'sex', 'birthdate', 'contact_number', 'email', 'email_verified_at', 'role', 'status', 'login_attempts', 'username', 'password', 'profile_picture_id', 'campus_id'];

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

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    // Custom methods

    public function roleIs(string $role): bool
    {
        return strtolower($this->role ?? '') === strtolower($role);
    }

    public function isSuperAdmin(): bool
    {
        return $this->roleIs('super admin');
    }

    public function isAdmin(): bool
    {
        return $this->roleIs('admin');
    }

    public function isLibrarian(): bool
    {
        return $this->roleIs('librarian');
    }

    public function isPatron(): bool
    {
        return $this->roleIs('patron');
    }


    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('permission', $permission)->exists();
    }

}
