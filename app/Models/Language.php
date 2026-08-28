<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /** @use HasFactory<LanguageFactory> */
    use AutoFormatter, HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    protected $formatter = [
        'code' => 'lowercase',
        'name' => 'titlecase',
    ];
}
