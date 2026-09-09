<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAuthor extends Model
{
    /** @use HasFactory<\Database\Factories\ItemAuthorFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'item_id',
        'authorship_id',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function authorship()
    {
        return $this->belongsTo(Authorship::class);
    }
}
