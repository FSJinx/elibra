<?php

namespace App\Models;

use Database\Factories\SystemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    /** @use HasFactory<SystemFactory> */
    use HasFactory;

    protected $fillable = [
        'key',
        'description',
        'value',
        'selections',
    ];

    protected $casts = [
        'selections' => 'array',
    ];
}
