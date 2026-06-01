<?php

namespace App\Models;

use Database\Factories\LibrarianSecondaryRolesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibrarianSecondaryRoles extends Model
{
    /** @use HasFactory<LibrarianSecondaryRolesFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'librarian_id',
        'library_role_id',
    ];
}
