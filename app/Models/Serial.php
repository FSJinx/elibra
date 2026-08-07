<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serial extends Model
{
    /** @use HasFactory<\Database\Factories\SerialFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'isbn_issn',
        'volume',
        'issue',
        'pages',
        'doi',
        'item_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
