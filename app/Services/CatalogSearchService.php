<?php

namespace App\Services;

use App\Models\CatalogIndex;

class CatalogSearchService
{
    public function __construct(
        private SearchQueryService $queryService
    ) {}

    public function search(
        string $query,
        array $filters = [],
        int $perPage = 10
    ) {
        $processed = $this->queryService->process($query);

        $search = CatalogIndex::search(
            $processed['search_query']
        );

        $filter = $this->buildFilters($filters);

        if ($filter) {
            $search->options([
                'filter' => $filter,
            ]);
        }

        return $search->paginate($perPage);
    }

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
            if (isset($filters[$field])) {
                $conditions[] = $field . ' = ' . (int) $filters[$field];
            }
        }

        return empty($conditions)
            ? null
            : implode(' AND ', $conditions);
    }
}