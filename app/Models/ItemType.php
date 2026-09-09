<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\ItemTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    /** @use HasFactory<ItemTypeFactory> */
    use AutoFormatter, HasFactory;

    protected $fillable = [
        'slug',
        'name',
    ];

    protected $formatter = [
        'name' => 'capitalize',
    ];
}
