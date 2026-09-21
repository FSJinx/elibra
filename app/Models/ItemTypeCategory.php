<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\ItemTypeCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTypeCategory extends Model
{
    /** @use HasFactory<ItemTypeCategoryFactory> */
    use HasFactory, AutoFormatter;

    protected $fillable = [
        'name',
        'code',
        'item_type_id',
    ];

    protected $formatter =[
        'name' => 'capitalize'
    ];
}
