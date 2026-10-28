<?php

namespace App\Services;

use App\Models\CatalogIndex;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogSearchService
{
    public const ITEM_RELATIONS = [
        'authors',
        'itemType',
        'itemTypeCategory',
        'library.campus',
        'language',
        'itemPublishers.publisher',
    ];

    public function __construct(
        private SearchQueryService $queryService
    ) {}

    public function show(int $itemId): ?array
    {
        $item = $this->itemQuery()
            ->with([
                'accessions' => fn ($query) => $query->whereNull('deleted_at'),
            ])
            ->find($itemId);

        if (! $item) {
            return null;
        }

        return [
            ...$this->formatItem($item),
            'accessions' => $item->accessions
                ->map(function ($copy) {
                    return [
                        'accession_number' => $copy->accession_number,
                        'status' => $copy->status,
                    ];
                })
                ->values()
                ->all(),
        ];
    }

    public function search(
        string $query,
        array $filters = [],
        int $perPage = 10
    ): LengthAwarePaginator {
        if (config('scout.driver') === 'meilisearch') {
            return $this->searchMeilisearch($query, $filters, $perPage);
        }

        // Local drivers read current SQL records without a search index or queue worker.
        $items = $this->itemQuery();
        $this->applyFilters($items, $filters);

        $terms = $this->queryService->process($query)['terms'];
        if ($terms === [] && trim($query) !== '') {
            $terms = [trim($query)];
        }

        foreach ($terms as $term) {
            $alternatives = $this->queryService->process($term)['expanded_terms'] ?: [$term];

            $items->where(function (Builder $matches) use ($alternatives) {
                foreach ($alternatives as $alternative) {
                    $like = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_strtolower($alternative)).'%';

                    foreach (['title', 'subtitle', 'description', 'call_number', 'keywords', 'isbn_issn', 'doi'] as $column) {
                        $matches->orWhereRaw("LOWER(items.$column) LIKE ? ESCAPE '!'", [$like]);
                    }
                    $matches->orWhereHas('authors', function (Builder $authors) use ($like) {
                        $authors->where(function (Builder $names) use ($like) {
                            foreach (['first_name', 'middle_name', 'last_name', 'suffix'] as $column) {
                                $names->orWhereRaw("LOWER($column) LIKE ? ESCAPE '!'", [$like]);
                            }
                        });
                    });
                    $matches->orWhereHas('itemPublishers.publisher', function (Builder $publisher) use ($like) {
                        $publisher->whereRaw("LOWER(name) LIKE ? ESCAPE '!'", [$like]);
                    });
                }
            });
        }

        $sort = $filters['sort'] ?? null;
        if (in_array($sort, ['title', 'publication_year'], true)) {
            $items->orderBy($sort === 'title' ? 'items.title' : 'publication_year', $filters['order'] ?? 'asc');
        }
        $results = $items
            ->orderBy('items.id')
            ->paginate($perPage);

        $results->setCollection(
            $results->getCollection()
                ->map(fn ($item) => $this->formatItem($item))
        );

        return $results;
    }

    private function applyFilters(Builder $items, array $filters): void
    {
        $libraryId = $filters['library_id'] ?? $filters['branch_id'] ?? null;
        if ($libraryId !== null && $libraryId !== '') {
            $items->where('items.library_id', $libraryId);
        }
        if (! empty($filters['campus_id'])) {
            $items->whereHas('library', function (Builder $library) use ($filters) {
                $library->where('campus_id', $filters['campus_id']);
            });
        }
        foreach (['item_type_id', 'item_type_category_id', 'department_id'] as $field) {
            if (! empty($filters[$field])) {
                $items->where("items.$field", $filters[$field]);
            }
        }
        if (! empty($filters['publication_year'])) {
            $items->whereHas('itemPublishers', function (Builder $publisher) use ($filters) {
                $publisher->where('year_published', $filters['publication_year']);
            });
        }
    }

    private function searchMeilisearch(string $query, array $filters, int $perPage): LengthAwarePaginator
    {
        $search = CatalogIndex::search(trim($query))->where('released', true);
        foreach (['campus_id', 'item_type_id', 'item_type_category_id', 'department_id', 'publication_year'] as $field) {
            if (! empty($filters[$field])) {
                $search->where($field, (int) $filters[$field]);
            }
        }
        $libraryId = $filters['library_id'] ?? $filters['branch_id'] ?? null;
        if ($libraryId !== null && $libraryId !== '') {
            $search->where('library_id', (int) $libraryId);
        }
        if (in_array($filters['sort'] ?? null, ['title', 'publication_year'], true)) {
            $search->orderBy($filters['sort'], $filters['order'] ?? 'asc');
        }

        $results = $search->paginate($perPage);
        $items = $this->itemQuery()
            ->whereIn('id', $results->getCollection()->pluck('item_id'))
            ->get()
            ->keyBy('id');

        $results->setCollection(
            $results->getCollection()
                ->filter(fn ($entry) => $items->has($entry->item_id))
                ->map(fn ($entry) => $this->formatItem($items->get($entry->item_id)))
                ->values()
        );

        return $results;
    }

    private function itemQuery(): Builder
    {
        return Item::query()
            ->where('released', true)
            ->with(self::ITEM_RELATIONS)
            ->withMax('itemPublishers as publication_year', 'year_published')
            ->withCount([
                'accessions as copies_count' => fn ($query) => $query->whereNull('deleted_at'),
                'accessions as available_copies' => fn ($query) => $query->whereNull('deleted_at')->where('status', 'available'),
            ]);
    }

    private function formatItem(Item $item): array
    {
        $publishers = $item->itemPublishers
            ->pluck('publisher.name')
            ->filter()
            ->unique()
            ->values();

        return [
            'id' => $item->id,
            'item_id' => $item->id,

            'title' => $item->title,
            'subtitle' => $item->subtitle,
            'description' => $item->description,
            'call_number' => $item->call_number,
            'keywords' => $item->keywords ?? [],

            'authors' => $item->authors
                ->map(function ($author) {
                    return trim(implode(' ', array_filter([
                        $author->first_name,
                        $author->middle_name,
                        $author->last_name,
                        $author->suffix,
                    ])));
                })
                ->values()
                ->all(),

            'item_type' => $item->itemType?->name,
            'item_type_category' => $item->itemTypeCategory?->name,
            'item_type_id' => $item->item_type_id,
            'item_type_category_id' => $item->item_type_category_id,
            'library' => $item->library?->name,
            'library_id' => $item->library_id,

            // Compatibility with existing OPAC location labels and filters.
            'branch' => $item->library?->name,
            'branch_id' => $item->library_id,
            'campus' => $item->library?->campus?->name,
            'campus_id' => $item->library?->campus_id,
            'department_id' => $item->department_id,
            'language' => $item->language?->name,
            'language_id' => $item->language_id,

            'publication_year' => $item->publication_year === null ? null : (int) $item->publication_year,
            'publisher' => $publishers->isEmpty() ? null : $publishers->implode(', '),
            'publishers' => $publishers->all(),
            'isbn_issn' => $item->isbn_issn,
            'edition' => $item->edition,
            'doi' => $item->doi,
            'volume' => $item->volume,
            'issue' => $item->issue,
            'pages' => $item->pages,

            'copies_count' => (int) $item->copies_count,
            'available_copies' => (int) $item->available_copies,
            'status' => $item->available_copies > 0 ? 'Available' : ($item->copies_count > 0 ? 'Unavailable' : 'No copies'),
        ];
    }
}
