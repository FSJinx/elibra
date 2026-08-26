<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory, SoftDeletes;
    // protected $hidden = [
    //     'electronic_file',
    // ];
    
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'call_number',
        'language',
        'publication_year',
        'keywords',
        'electronic_file',
        'item_type_id',
        'item_type_category_id',
        'branch_id'
    ];

    // NOT YET implemented
    // public function book()
    // {
    //     return $this->hasOne(Book::class);
    // }

    public function academic()
    {
        return $this->hasOne(Academic::class);
    }

    public function serial()
    {
        return $this->hasOne(Serial::class);
    }

    public function authors()
    {
        return $this->belongsToMany(
            Author::class,
            'item_authors',
            'item_id',
            'author_id',
        );
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function itemTypeCategory()
    {
        return $this->belongsTo( ItemTypeCategory::class);
    }
}
