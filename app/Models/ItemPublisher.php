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

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
