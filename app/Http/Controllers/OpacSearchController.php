<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogSearchRequest;
use App\Services\CatalogSearchService;
use Illuminate\Http\JsonResponse;

class OpacSearchController extends Controller
{
    public function __construct(
        private CatalogSearchService $searchService
    ) {}

    public function show(string $itemId): JsonResponse
    {
        $item = ctype_digit($itemId) && (int) $itemId > 0
            ? $this->searchService->show((int) $itemId)
            : null;

        if (! $item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Item retrieved successfully.',
            'data' => $item,
        ]);
    }

    public function search(CatalogSearchRequest $request): JsonResponse
    {
        $query = $request->input('query') ?? '';

        $results = $this->searchService->search(
            $query,
            $request->validated(),
            $request->integer('per_page', 10)
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Search Results Retrieved Successfully!',
            'data' => [
                'query' => $query,
                'results' => $results,
            ],
        ]);
    }
}
