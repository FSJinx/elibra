<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'keywords',
        'branch_id',
    ];

    public function academic()
    {
        return $this->hasOne(Academic::class);
    }
}
