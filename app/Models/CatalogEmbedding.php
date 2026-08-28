<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogEmbedding extends Model
{
    protected $fillable = [
        'catalog_index_id',
        'embedding',
        'model',
        'dimensions',
    ];

    protected $casts = [
        'embedding' => 'array',
    ];

    public function catalogIndex(): BelongsTo
    {
        return $this->belongsTo(
            CatalogIndex::class,
            'catalog_index_id'
        );
    }
}
