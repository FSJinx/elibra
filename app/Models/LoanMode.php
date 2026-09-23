<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\LoanModeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanMode extends Model
{
    /** @use HasFactory<LoanModeFactory> */
    use HasFactory, AutoFormatter;

    protected $fillable = [
        'slug', 'name',
    ];

    protected $formatter = ['name' => 'capitalize'];
}
