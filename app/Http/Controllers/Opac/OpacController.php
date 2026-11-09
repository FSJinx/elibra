<?php

namespace App\Http\Controllers\Opac;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class OpacController extends Controller
{
    protected function relationships(): array
    {
        return [
            'accessions' => fn ($a) => $a->whereNotNull('accession_number'),
            'authors',
            'coverMedia:id,file_name,file_path',
            'department:id,name',
            'itemType:id,slug,name,loanable',
            'itemTypeCategory:id,code,name',
            'language:id,code,name',
            'library:id,name,campus_id',
            'library.campus:id,name',
            'publisher:id,name',
        ];
    }

    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $data = CacheService::remember(
            CacheService::OPAC,
            $filters,
            now()->addMinutes(10),
            function () use ($filters) {
                $columns = ['*'];

                return Item::query()
                // ===== RELATIONSHIPS =====
                    ->with(self::relationships())
                // ====== SEARCH QUERY =====
                    ->when($filters['search'] ?? null, function ($q, $s) {
                        return $q->where('title', 'LIKE', "%$s%")
                            ->orWhere('subtitle', 'LIKE', "%$s%");
                    })
                // ====== RELEASED TO OPAC =====
                    ->where('released', true)
                // ====== PAGINATIONS & SORTING =====
                    ->orderBy($filters['sort'], $filters['order'])
                    ->paginate(
                        $filters['per_page'],
                        $columns,
                        'opac',
                        $filters['page']
                    );
            }
        );

        return $this->response(data: $data);
    }

    public function show(Item $item)
    {
        return $this->response(data: $item->load(self::relationships()));
    }
}
