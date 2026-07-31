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

    protected $fillable = ['user_id', 'branch_id', 'role', 'tools'];

    protected $casts = [
        'tools' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}


/**
 * Admin God -> lives in ech
 * Echague Campus -> Admin 1
 * Admin 1 -> create University Library (branch), create account for library admin = Library Admin Echague
 * Library Admin Echague -> add sections to branch, create accounts for librarians, assign librarians to section
 * 
 * Angadanan Campus -> Admin 2
 * -> create department, program, branches -> create library admin account, manage users 
 * -> library admin -> manage librarians, branch
 * 
 */