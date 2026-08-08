<?php

namespace App\Services;

use Illuminate\Http\Request;

class QueryService
{
    public static function filters(
        Request $request,
        int $defaultPerPage = 15,
        int $maxPerPage = 100
    ): array {
        $perPage = $request->integer('per_page', $defaultPerPage);

        $perPage = max(1, min($perPage, $maxPerPage));

        return [
            'search' => trim($request->query('query', '')),
            'sort' => $request->query('sort', 'id'),
            'order' => strtolower($request->query('order', 'asc')),
            'page' => $request->integer('page', 1),
            'per_page' => $perPage,
        ];
    }
}