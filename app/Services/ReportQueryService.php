<?php

namespace App\Services;

use App\Enums\ReportType;
use App\Http\Requests\StoreReportRequest;
use Illuminate\Support\Arr;

class ReportQueryService
{
    public static function filters(ReportType $type, StoreReportRequest $request): array
    {
        $filters = $request->input('filters');

        return array_merge(Arr::only($filters, $type->filters()), [
            'format' => $request->input('format') ?: 'pdf',
            'columns' => array_values(array_unique([...$type->columns(), ...($filters['columns'] ?? [])])),
            'created_from' => $filters['created_from'] ?: null,
            'created_to' => $filters['created_to'] ?: now()->toDateString(),
        ]);
    }
}
