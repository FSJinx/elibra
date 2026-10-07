<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Models\Item;
use App\Services\CacheService;
use App\Services\ItemService;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ItemController extends Controller
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

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

    public function temporaryIndex(Request $request)
    {
        $user = $request->user();

        $filters = QueryService::filters($request);

        $params = [
            ...$filters,
            // 'branch_id' => $request->query('branch_id', ''),
            // 'campus_id' => $request->query('campus_id', ''),
        ];

        $catalog = $this->itemService->index($params, $user);

        return $this->response(
            'success',
            'Catalog retrieved successfully.',
            $catalog->toArray(),
            200
        );

    }

    /**
     * Display the specified item.
     */
    public function show(string $id)
    {
        $item = $this->itemService->show($id);

        return $this->response(message: 'Catalog received successfully', data: $item->toArray());
    }

    public function store(StoreItemRequest $request, Item $item)
    {
        DB::beginTransaction();

        $newItem = array_merge($request->validated(), ['library_id' => $this->user()->librarian->id]);

        try {
            $item = Item::create($newItem);

            $item->refresh();

            DB::commit();

            CacheService::invalidate(CacheService::ITEMS);

            return $this->response(
                'success',
                'Item created successfully',
                $item->toArray(),
                201
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
