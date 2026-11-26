<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPublisher extends Model
{
    protected $fillable = [
        'item_id',
        'publisher_id',
        'year_published',
    ];

    protected $casts = [
        'year_published' => 'integer',
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
