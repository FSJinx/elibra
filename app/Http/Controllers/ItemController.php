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

        // $user = $request->user();
        $user = $this->user();

        $filters = QueryService::filters($request);

        $branchId = $user->isLibrarian()
            ? $user->librarian?->branch_id
            : null;
        $campusId = $user->isAdmin() ? $user->campus_id : null;

        $parameters = array_merge(
            $filters,
            [
                'role' => $user->role,
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
                $query->with('coverMedia');

                if ($user->isSuperAdmin()) {
                    // Super admins can see every item.
                    $query->withTrashed();
                } elseif ($user->isAdmin()) {
                    $query->whereHas('branch', function ($query) use ($campusId) {
                        $query->where('campus_id', $campusId);
                    });
                } elseif ($user->isLibrarian()) {
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
                        'item_type_id',
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

    public function temporaryIndex()
    {
        $user = $this->user();

        if ($user->isAdmin()) {
            $items = $user->campus->items();
        } elseif ($user->isLibrarian()) {
            $items = $user->branch->items();
        }

        // Paginate Items
        $items = $items->paginate(
            15,
            ['*'],
            'page',
            1
        );

        return $this->response(
            'success',
            'Items retrieved successfully.',
            $items->toArray(),
            200
        );
    }

    /**
     * Display the specified item.
     */
    public function show(Request $request, Item $item)
    {
        $this->authorize('viewAny', Item::class);

        $user = $request->user();

        if ($user->isAdmin() && $item->branch?->campus_id !== $user->campus_id) {
            abort(404);
        }

        if (! $user->isSuperAdmin() && $user->isLibrarian() && $item->branch_id !== $user->librarian?->branch_id) {
            abort(404);
        }

        $item->load([
            'book',
            'academic',
            'serial',
            'authors',
            'itemType',
            'itemTypeCategory',
            'branch',
            'language',
            'coverMedia',
        ]);

        return $this->response(
            'success',
            'Item retrieved successfully.',
            $item->toArray(),
            200
        );
    }
}
