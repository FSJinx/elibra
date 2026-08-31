<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class CatalogIndex extends Model
{
    use SoftDeletes, Searchable;

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

    protected $casts = [
        'indexed_at' => 'datetime',
        'publication_year' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function toSearchableArray(): array
    {
        $item = $this->item()->with([
            'authors',
            'itemType',
            'itemTypeCategory',
            'branch',
            'language',
        ])->first();

        return [
            'id' => $this->id,
            'item_id' => $this->item_id,

            // Main catalog fields
            'title' => $item?->title,
            'subtitle' => $item?->subtitle,
            'description' => $item?->description,
            'call_number' => $item?->call_number,
            'publication_year' => $item?->publication_year,

            // Search fields
            'keywords' => $this->normalizeKeywords($item?->keywords),

            'authors' => $item?->authors?->map(function ($author) {
                return trim(implode(' ', array_filter([
                    $author->first_name,
                    $author->middle_name,
                    $author->last_name,
                    $author->suffix,
                ])));
            })->values()->all() ?? [],

            'item_type' => $item?->itemType?->name,
            'item_type_category' => $item?->itemTypeCategory?->name,
            'branch' => $item?->branch?->name,
            'language' => $item?->language?->name,

            // Complete text
            'content' => $this->content,

            // Filters
            'branch_id' => $this->branch_id,
            'item_type_id' => $this->item_type_id,
            'item_type_category_id' => $this->item_type_category_id,
            'department_id' => $this->department_id,
        ];
    }

    protected function normalizeKeywords($keywords): array
    {
        if (empty($keywords)) {
            return [];
        }

        if (is_array($keywords)) {
            return array_values(array_filter(
                array_map('trim', $keywords)
            ));
        }

        return array_values(array_filter(
            array_map('trim', explode(',', $keywords))
        ));
    }
}