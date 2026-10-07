<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPublisher extends Model
{
    /** @use HasFactory<\Database\Factories\ItemPublisherFactory> */
    use HasFactory;

    protected $fillable = [
        'year_published',
        'item_id',
        'publisher_id',
    ];
}
