<?php

namespace App\Services;

use Illuminate\Http\Request;

class QueryService
{
    public static function filters(
        Request $request,
        mixed $page = 1,
        mixed $perPage = 25,
        mixed $maxPerPage = 100,
        ?string $sort = 'created_at',
        ?string $order = 'desc',
    ): array {
        $reqOrder = $request->string('order')->trim()->lower()->toString();
        $per_page = max(1, min($request->integer('per_page', $perPage), $maxPerPage));

        return [
            'search' => $request->string('query')->trim()->toString() ?: null,
            'sort' => $request->string('sort')->trim()->toString() ?: $sort,
            'order' => in_array($reqOrder, ['asc', 'desc'], true) ? $reqOrder : $order,
            'page' => $request->integer('page', null) ?: $page,
            'per_page' => $per_page,
        ];
    }
}
