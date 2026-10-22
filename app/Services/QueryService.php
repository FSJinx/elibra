<?php

namespace App\Services;

use Illuminate\Http\Request;

class QueryService
{
    public static function filters(
        Request $request,
        int $defaultPerPage = 25,
        int $maxPerPage = 100
    ): array {
        $perPage = $request->integer('per_page', $defaultPerPage);
        $sort = trim((string) $request->query('sort', ''));
        $order = strtolower((string) $request->query('order', 'desc'));

        $perPage = max(1, min($perPage, $maxPerPage));

        return [
            'search' => trim($request->query('query', '')),
            'sort' => $sort !== '' ? $sort : 'created_at',
            'order' => in_array($order, ['asc', 'desc'], true) ? $order : 'desc',
            'page' => $request->integer('page', 1),
            'per_page' => $perPage,
        ];
    }
}
