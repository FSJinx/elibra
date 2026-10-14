<?php

namespace App\Http\Controllers\Librarian\Collections;

use App\Http\Controllers\Controller;
use App\Services\ItemService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(Request $request)
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

    public function show(string $id)
    {
        $item = $this->itemService->show($id);

        return $this->response(message: 'Catalog received successfully', data: $item->toArray());
    }
}
