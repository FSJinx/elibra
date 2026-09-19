<?php

namespace App\Http\Controllers\Librarian\Collections;

use App\Http\Controllers\Controller;
use App\Models\Item;
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

    public function search(Request $request)
    {
        $user = $this->user();
        abort_unless($user && ($user->isAdmin() || $user->isLibrarian()), 403, "You don't have the permission to access this resource.");

        $items = Item::query();

        if ($user->isAdmin()) {
            $items->whereHas('branch', function ($query) use ($user) {
                $query->where('campus_id', $user->campus_id);
            });
        } else {
            $items->where('branch_id', $user->librarian?->branch_id);
        }

        if ($query = trim((string) $request->input('query', ''))) {
            $searchTerms = array_values(array_filter(
                preg_split('/\s+/u', $query),
                static fn (string $term): bool => $term !== ''
            ));

            $searchFields = [
                'title' => 8,
                'subtitle' => 5,
                'keywords' => 4,
                'description' => 3,
            ];

            $items->where(function ($searchQuery) use ($searchTerms, $searchFields) {
                foreach ($searchTerms as $term) {
                    $searchQuery->orWhere(function ($termQuery) use ($term, $searchFields) {
                        foreach (array_keys($searchFields) as $field) {
                            $termQuery->orWhere($field, 'LIKE', "%{$term}%");
                        }
                    });
                }
            });

            $rankBindings = [];
            $rankParts = [];

            foreach ($searchTerms as $term) {
                foreach ($searchFields as $field => $weight) {
                    $rankParts[] = "CASE WHEN LOWER({$field}) LIKE LOWER(?) THEN {$weight} ELSE 0 END";
                    $rankBindings[] = "%{$term}%";
                }
            }

            $items->orderByRaw(implode(' + ', $rankParts).' DESC', $rankBindings);
        }

        $items = $items->paginate(
            $request->integer('per_page', 10),
            ['*'],
            'page',
            $request->integer('page', 1)
        );

        return $this->response(
            'success',
            'Items retrieved successfully.',
            $items->toArray(),
            200
        );
    }
}
