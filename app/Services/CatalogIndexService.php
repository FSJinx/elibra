<?php

namespace App\Services;

use App\Models\CatalogIndex;
use App\Models\Item;

class CatalogIndexService
{
    public function index(Item $item): ?CatalogIndex
    {
        $item->load([
            'authors',
            'book',
            'academic.department',
            'serial',
            'itemType',
            'itemTypeCategory',
            'branch',
            'language'
        ]);

        $content = $this->buildContent($item);

        $catalogIndex = CatalogIndex::updateOrCreate(
            [
                'item_id' => $item->id,
            ],
            [
                'content' => $content,

                // Filter metadata
                'branch_id' => $item->branch_id,
                'item_type_id' => $item->item_type_id,
                'item_type_category_id' => $item->item_type_category_id,
                'department_id' => $item->academic?->department_id,
                'publication_year' => $item->publication_year,
                'language' => $item->language?->name,

                'indexed_at' => now(),
            ]
        );

        // Sync CatalogIndex with Meilisearch
        $catalogIndex->searchable();

        return $catalogIndex;

    }

    private function buildContent(Item $item): string
    {
        $authors = $this->buildAuthors($item);

        $keywords = $item->keywords;

        if (is_array($keywords)) {
            $keywords = implode(', ', $keywords);
        }

        $content = [
            "Title: {$item->title}",
            "Subtitle: {$item->subtitle}",
            "Description: {$item->description}",
            "Authors: {$authors}",
            "Keywords: {$keywords}",        
        ];

        // Academic
        if ($item->academic) {
            $subjects = $item->academic->subjects ?? [];

            if (is_array($subjects)) {
                $subjects = implode(', ', $subjects);
            }

            $content[] = "Subjects: {$subjects}";
            $content[] = "DOI: {$item->academic->doi}";
        }   

        // Serial
        if ($item->serial) {
            $content[] = "ISSN/ISBN: {$item->serial->isbn_issn}";
            $content[] = "Volume: {$item->serial->volume}";
            $content[] = "Issue: {$item->serial->issue}";
            $content[] = "DOI: {$item->serial->doi}";
        }

        // Books
        if ($item->book) {
            $content[] = "ISSN/ISBN: {$item->book->isbn_issn}";
            $content[] = "Edition: {$item->book->edition}";
        }

        return implode("\n", array_filter($content));
    }

    private function buildAuthors(Item $item): string
    {
        return $item->authors
            ->map(function ($author) {
                return trim(implode(' ', array_filter([
                    $author->first_name,
                    $author->middle_name,
                    $author->last_name,
                    $author->suffix,
                ])));
            })
            ->implode(', ');
    }
}