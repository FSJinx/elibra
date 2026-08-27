<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogIndex extends Model
{
    protected $fillable = [
        'item_id',
        'content',
        'branch_id',
        'item_type_id',
        'item_type_category_id',
        'department_id',
        'publication_year',
        'language',
        'indexed_at',
    ];
        
    protected $cast = [
        'indexed_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
