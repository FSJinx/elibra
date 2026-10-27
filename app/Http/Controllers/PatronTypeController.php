<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatronTypeRequest;
use App\Http\Requests\UpdatePatronTypeRequest;
use App\Models\PatronType;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class PatronTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $types = CacheService::remember(
            CacheService::PATRON_TYPES,
            $filters,
            now()->addHour(),
            function () use ($filters) {
                $type = PatronType::query();

                return $type->orderBy($filters['sort'], $filters['order'])->paginate(
                    $filters['per_page'],
                    ['*'],
                    'page',
                    $filters['page']
                );
            }
        );

        return $this->response(
            'success',
            'Patron Types retrieved successfully',
            $types->toArray(),
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatronTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PatronType $patronType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PatronType $patronType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatronTypeRequest $request, PatronType $patronType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatronType $patronType)
    {
        //
    }
}
