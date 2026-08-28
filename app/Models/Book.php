<?php

namespace App\Models;

use Database\Factories\BooksFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<BooksFactory> */
    use HasFactory;

    protected $fillable = [
        'edition',
        'isbn_issn',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
