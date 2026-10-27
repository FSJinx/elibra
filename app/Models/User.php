<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use AutoFormatter, HasFactory, Notifiable, SoftDeletes;

    protected $appends = ['name'];

    protected $fillable = [
        // Personal Information
        'last_name',
        'first_name',
        'middle_initial',
        'sex',
        'birthdate',

        //  Account Info
        'role',
        'contact_number',
        'email',
        'email_verified_at',
        'username',
        'password',

        //  States
        'status',
        'login_attempts',
        'registration',

        //  Foreign Keys
        'profile_picture_id',
        'campus_id',
    ];

    protected $formatter = [
        'first_name' => 'titlecase',
        'last_name' => 'titlecase',
        'middle_initial' => 'uppercase',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->first_name,
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn () => (object) [
            'full' => "{$this->first_name} {$this->last_name}",
            'formal' => "{$this->last_name}, {$this->first_name}",
        ]);
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($user) {
            $user->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function librarian()
    {
        return $this->hasOne(Librarian::class, 'user_id');
    }

    public function profilePicture()
    {
        return $this->belongsTo(Media::class, 'profile_picture_id');
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

    public function library()
    {
        return $this->hasOneThrough(
            Library::class,
            Librarian::class,
            'user_id',
            'id',
            'id',
            'library_id'
        );
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
        return $this->roleIs('super_admin');
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
