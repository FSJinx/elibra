<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Item::class);

        $user = $request->user();

        $filters = QueryService::filters($request);

        $branchId = $user->isSuperAdmin()
            ? null
            : $user->librarian?->branch_id;
        $campusId = $user->isAdmin() ? $user->campus_id : null;

        $parameters = array_merge(
            $filters,
            [
                'branch_id' => $branchId,
                'campus_id' => $campusId,
            ]
        );

        $items = CacheService::remember(
            CacheService::ITEMS,
            $parameters,
            now()->addMinutes(10),
            function () use ($user, $branchId, $campusId, $filters) {

                $query = Item::query();

                if ($user->isAdmin()) {
                    $query->whereHas('branch', function ($query) use ($campusId) {
                        $query->where('campus_id', $campusId);
                    });
                } elseif (! $user->isSuperAdmin()) {
                    $query->where('branch_id', $branchId);
                }

                return $query->paginate(
                    $filters['per_page'],
                    [
                        'id',
                        'title',
                        'subtitle',
                        'call_number',
                        'publication_year',
                    ],
                    'page',
                    $filters['page']
                );
            }
        );

        if ($items->isEmpty()) {
            return $this->response(
                'success',
                'No item can be found.',
                [],
                200
            );
        }

        return $this->response(
            'success',
            'Items retrieved successfully.',
            $items->toArray(),
            200
        );
    }
}
