<?php

namespace App\Services;

use App\Models\CatalogIndex;
use App\Models\Item;

class CatalogIndexService
{
    public function index(Item $item): ?CatalogIndex
    {
        if (! $item->released || $item->trashed()) {
            CatalogIndex::where('item_id', $item->id)->get()->each->delete();

            return null;
        }

        $item->load(CatalogSearchService::ITEM_RELATIONS);
        $item->loadMax('itemPublishers as publication_year', 'year_published');
        $authors = $item->authors
            ->map(function ($author) {
                return trim(implode(' ', array_filter([
                    $author->first_name,
                    $author->middle_name,
                    $author->last_name,
                    $author->suffix,
                ])));
            })
            ->implode(', ');

        $content = implode("\n", array_filter([
            $item->title,
            $item->subtitle,
            $item->description,
            $item->call_number,
            $authors,
            implode(', ', $item->keywords ?? []),
            $item->isbn_issn,
            $item->doi,
            $item->edition,
            $item->volume,
            $item->issue,
            $item->itemPublishers->pluck('publisher.name')->filter()->implode(', '),
        ]));

        $entry = CatalogIndex::withTrashed()->firstOrNew(['item_id' => $item->id]);
        $entry->fill([
            'content' => $content,
            'campus_id' => $item->library?->campus_id,
            'library_id' => $item->library_id,
            'item_type_id' => $item->item_type_id,
            'item_type_category_id' => $item->item_type_category_id,
            'department_id' => $item->department_id,
            'publication_year' => $item->publication_year,
            'language' => $item->language?->name,
            'indexed_at' => now(),
        ]);
        $entry->deleted_at = null;
        $entry->save();

        return $entry;
    }
}
