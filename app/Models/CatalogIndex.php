<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogIndex extends Model
{
    use SoftDeletes;

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

    public function embedding()
    {
            return $this->hasOne(CatalogEmbedding::class, 'catalog_index_id');
    }
}
