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
        'branch_id'
    ];

    public function academic()
    {
        return $this->hasOne(Academic::class);
    }
}
