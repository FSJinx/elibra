<?php

namespace App\Models;

use Database\Factories\AuthorshipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorship extends Model
{
    /** @use HasFactory<AuthorshipFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
