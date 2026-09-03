<?php

namespace App\Services;

use App\Models\CatalogIndex;
use App\Models\Item;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogSearchService
{
    public function __construct(
        private SearchQueryService $queryService
    ) {}

    public function search(
        string $query,
        array $filters = [],
        int $perPage = 10
    ): LengthAwarePaginator {
        /*
        |--------------------------------------------------------------------------
        | 1. Process search query
        |--------------------------------------------------------------------------
        */

        $processed = $this->queryService->process($query);

        /*
        |--------------------------------------------------------------------------
        | 2. Search Meilisearch
        |--------------------------------------------------------------------------
        */

        $search = CatalogIndex::search(
            $processed['search_query']
        );

        /*
        |--------------------------------------------------------------------------
        | 3. Build filters
        |--------------------------------------------------------------------------
        */

        $options = [];

        $filter = $this->buildFilters($filters);

        if ($filter) {
            $options['filter'] = $filter;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Sorting
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['sort'])) {
            $allowedSorts = [
                'title',
                'publication_year',
            ];

            if (in_array($filters['sort'], $allowedSorts, true)) {
                $order = $filters['order'] ?? 'asc';

                $options['sort'] = [
                    $filters['sort'] . ':' . $order,
                ];
            }
        }

        if (!empty($options)) {
            $search->options($options);
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Get Meilisearch results
        |--------------------------------------------------------------------------
        */

        $searchResults = $search->paginate($perPage);

        /*
        |--------------------------------------------------------------------------
        | 6. Get CatalogIndex models from current page
        |--------------------------------------------------------------------------
        */

        $catalogs = collect($searchResults->items());

        /*
        |--------------------------------------------------------------------------
        | 7. Load related Items in one query
        |--------------------------------------------------------------------------
        */

        $itemIds = $catalogs
            ->pluck('item_id')
            ->filter()
            ->unique()
            ->values();

        $items = Item::query()
            ->with([
                'authors',
                'itemType',
                'itemTypeCategory',
                'branch',
                'language',
            ])
            ->whereIn('id', $itemIds)
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | 8. Transform CatalogIndex → OPAC result
        |--------------------------------------------------------------------------
        */

        $data = $catalogs->map(
            function (CatalogIndex $catalog) use ($items) {

                $item = $items->get($catalog->item_id);

                return [
                    /*
                    |--------------------------------------------------------------------------
                    | IDs
                    |--------------------------------------------------------------------------
                    */

                    'id' => $catalog->id,

                    'item_id' => $catalog->item_id,

                    /*
                    |--------------------------------------------------------------------------
                    | Bibliographic information
                    |--------------------------------------------------------------------------
                    */

                    'title' => $item?->title,

                    'subtitle' => $item?->subtitle,

                    'description' => $item?->description,

                    'call_number' => $item?->call_number,

                    /*
                    |--------------------------------------------------------------------------
                    | Authors
                    |--------------------------------------------------------------------------
                    */

                    'authors' => $item?->authors
                        ?->map(function ($author) {
                            return trim(
                                implode(
                                    ' ',
                                    array_filter([
                                        $author->first_name,
                                        $author->middle_name,
                                        $author->last_name,
                                        $author->suffix,
                                    ])
                                )
                            );
                        })
                        ->values()
                        ->all() ?? [],

                    /*
                    |--------------------------------------------------------------------------
                    | Publication
                    |--------------------------------------------------------------------------
                    */

                    'publication_year' =>
                        $item?->publication_year
                        ?? $catalog->publication_year,

                    /*
                    |--------------------------------------------------------------------------
                    | Classification
                    |--------------------------------------------------------------------------
                    */

                    'item_type' =>
                        $item?->itemType?->name,

                    'item_type_category' =>
                        $item?->itemTypeCategory?->name,

                    /*
                    |--------------------------------------------------------------------------
                    | Location
                    |--------------------------------------------------------------------------
                    */

                    'branch' =>
                        $item?->branch?->name,

                    'language' =>
                        $item?->language?->name,

                    /*
                    |--------------------------------------------------------------------------
                    | Filter IDs
                    |--------------------------------------------------------------------------
                    */

                    'branch_id' =>
                        $catalog->branch_id,

                    'item_type_id' =>
                        $catalog->item_type_id,

                    'item_type_category_id' =>
                        $catalog->item_type_category_id,

                    'department_id' =>
                        $catalog->department_id,
                ];
            }
        )->values();

        /*
        |--------------------------------------------------------------------------
        | 9. Create a new paginator
        |--------------------------------------------------------------------------
        */

        return new LengthAwarePaginator(
            $data,
            $searchResults->total(),
            $searchResults->perPage(),
            $searchResults->currentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    /**
     * Build Meilisearch filter expression.
     */
    protected function buildFilters(array $filters): ?string
    {
        $allowedFilters = [
            'branch_id',
            'item_type_id',
            'item_type_category_id',
            'department_id',
            'publication_year',
        ];

        $conditions = [];

        foreach ($allowedFilters as $field) {
            if (
                isset($filters[$field]) &&
                $filters[$field] !== ''
            ) {
                $conditions[] =
                    $field . ' = ' . (int) $filters[$field];
            }
        }

        return empty($conditions)
            ? null
            : implode(' AND ', $conditions);
    }
}