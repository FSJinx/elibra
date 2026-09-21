<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogSearchRequest;
use App\Services\CatalogSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpacSearchController extends Controller
{
    public function __construct(
        private CatalogSearchService $searchService
    ) {}

    public function show(int $itemId): JsonResponse
    {
        $item = $this->searchService->show($itemId);

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
        $results = $this->searchService->search(
            $request->input('query', ''),
            $request->validated(),
            $request->integer('per_page', 10)
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Search Results Retrieved Successfully!',
            'data' => [
                'query' => $request->input('query'),
                'results' => $results,
            ],
        ]);
    }
}
