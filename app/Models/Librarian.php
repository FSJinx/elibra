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

    ];
}
