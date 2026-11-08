<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use App\Services\CacheService;
use App\Services\ItemService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        // Complete Filter
        $filters = array_merge($filters, [
            'search' => trim($filters['search'], '-'),
            'item_type_id' => $request->input('item_type_id'),
            'item_type_category_id' => $request->input('item_type_category_id'),
            'include_cover_media' => true,
        ]);

        $data = CacheService::remember(
            CacheService::ITEMS,
            $filters,
            now()->addHour(),
            function () use ($filters) {
                $item = Item::query()->with('coverMedia');

                $search = $filters['search'] ?? null;

                // ============== Search ============
                $item->when($search, function ($q) use ($search) {
                    return $q->where(function ($qq) use ($search) {
                        return $qq->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('subtitle', 'LIKE', "%{$search}%")
                            ->orWhereHas('accessions', fn ($qq2) => $qq2->where('accession_number', 'LIKE', "%{$search}%")
                            );
                    });
                });

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
            'acquisition_mode',
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

    public function store(StoreItemRequest $request)
    {
        $newItem = array_merge($request->validated(), [
            'library_id' => $this->user()->librarian?->library_id,
        ]);
        $item = $this->itemService->create($newItem);

        return $this->response(
            'success',
            'Item created successfully',
            $item->toArray(),
            201
        );
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $item = $this->itemService->update($item, $request->validated());

        return $this->response(
            'success',
            'Item updated successfully',
            $item->toArray(),
            200
        );
    }
}
