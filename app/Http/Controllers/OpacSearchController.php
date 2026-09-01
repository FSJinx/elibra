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

    public function search(CatalogSearchRequest $request): JsonResponse
    {
        $results = $this->searchService->search(
            $request->input('q', ''),
            $request->validated(),
            $request->integer('per_page', 10)
        );

        if($results->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No catalog result found.',
                'data' => [
                    'query' => $request->input('q'),
                    'results' => [],
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Search Results Retrieved Successfully!',
            'data' => [
                'query' => $request->input('q'),
                'results' => $results,
            ],
        ]);
    }
}
