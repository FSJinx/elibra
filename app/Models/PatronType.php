<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatronType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'key',
        'name',
        'description',

        'validity_period',
        'has_campus',
        'has_program',
        'has_department',
    ];

    protected $casts = [
        'has_campus' => 'boolean',
        'has_program' => 'boolean',
        'has_department' => 'boolean',
    ];
}
