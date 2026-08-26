<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    // use SoftDeletes;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
    ];



}
