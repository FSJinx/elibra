<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcquisitionMode extends Model
{
    /** @use HasFactory<\Database\Factories\AcquisitionModeFactory> */
    use HasFactory, AutoFormatter;

    protected $fillable = [
        'name',
        'description',

        'has_price',
    ];

    protected $casts = [
        'has_price' => 'boolean'
    ];

    protected $formatter = [
        'name' => 'capitalize'
    ];
}
