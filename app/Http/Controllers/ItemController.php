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

        $libraryId = $user->isLibrarian()
            ? $user->librarian?->library_id
            : null;
        $campusId = $user->isAdmin() ? $user->campus_id : null;

        $parameters = array_merge(
            $filters,
            [
                'role' => $user->role,
                'library_id' => $libraryId,
                'campus_id' => $campusId,
            ]
        );

        $items = CacheService::remember(
            CacheService::ITEMS,
            $parameters,
            now()->addMinutes(10),
            function () use ($user, $libraryId, $campusId, $filters) {

                $query = Item::query();
                $query->with('coverMedia');

                if ($user->isSuperAdmin()) {
                    // Super admins can see every item.
                    $query->withTrashed();
                } elseif ($user->isAdmin()) {
                    $query->whereHas('library', function ($query) use ($campusId) {
                        $query->where('campus_id', $campusId);
                    });
                } elseif ($user->isLibrarian()) {
                    $query->where('library_id', $libraryId);
                }

                return $query->orderBy('created_at', 'desc')
                    ->paginate(
                        $filters['per_page'],
                        [
                            'id',
                            'title',
                            'subtitle',
                            'call_number',
                            'item_type_id',
                            'released',
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
            $items,
            200
        );
    }

    public function indexV2(Request $request)
    {
        $filters = QueryService::filters($request);

        // Complete Filter
        $filters = array_merge($filters, [
            'search' => trim($filters['search'], '-'),
            'item_type_id' => $request->input('item_type_id'),
            'item_type_category_id' => $request->input('item_type_category_id'),
        ]);

        $data = CacheService::remember(
            CacheService::ITEMS,
            $filters,
            now()->addHour(),
            function () use ($filters) {
                $item = Item::query();

                // ============== Search ============
                $item->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($w) => $w->where('title', 'LIKE', "%$s%")->orWhere('subtitle', 'LIKE', "%$s%")));

                // ============== Item Type ============
                $item->when($filters['item_type_id'] ?? null, function ($i, $param) {
                    $i->where('item_type_id', $param);
                });

                // ============== Item Category ============
                $item->when($filters['item_type_category_id'] ?? null, function ($i, $param) {
                    $i->where('item_type_category_id', $param);
                });

                return $item
                    ->orderBy($filters['sort'], $filters['order'])
                    ->paginate(
                        $filters['per_page'],
                        [
                            '*',
                        ],
                        'page',
                        $filters['page']
                    );
            }

        );

        return $this->response(data: $data);
    }

    public function authors(Item $item)
    {
        $data = $item->authors()->get();

        return $this->response(data: $data);
    }

    public function accessions(Item $item)
    {
        $data = $item->accessions()->get();

        return $this->response(data: $data);
    }

    public function acquisitions(Item $item)
    {
        $data = $item->acquisitions()->with([
            'receiver:last_name,first_name',
            'acquisition_lines' => fn ($q) => $q->where('item_id', $item->id)->withCount('accessions as quantity'),
        ])->get()->makeHidden('pivot');

        return $this->response(data: $data);
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

            $item->syncAuthors($request->input('authors'));
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
