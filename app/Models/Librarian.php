<?php

namespace App\Models;

use Database\Factories\LibrarianFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Librarian extends Model
{
    /** @use HasFactory<LibrarianFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'primary_role_id',
        'tools',
    ];

    protected $casts = [
        'tools' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(LibraryRole::class, 'primary_role_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function primary_role()
    {
        return $this->belongsTo(LibraryRole::class, 'primary_role_id');
    }
}
