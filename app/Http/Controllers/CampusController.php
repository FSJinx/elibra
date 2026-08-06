<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampusRequest;
use App\Http\Requests\UpdateCampusRequest;
use App\Models\Campus;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        [
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
        ] = QueryService::filters($request);

        $campuses = CacheService::remember(
            CacheService::CAMPUSES,
            [
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
            ],
            now()->addMinutes(10),
            function () use ($search, $sort, $order) {

                $allowedSortFields = ['name', 'code'];

                $query = Campus::query();

                if (!empty($search)) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }

                if (is_array($sort) && !empty($sort)) {
                    foreach ($sort as $field) {
                        if (in_array($field, $allowedSortFields, true)) {
                            $query->orderBy(
                                $field,
                                $order === 'desc' ? 'desc' : 'asc'
                            );
                        }
                    }
                } else {
                    $query->orderBy('name');
                }

                return $query->select('name', 'code', 'address')->get();
            }
        );

        // No exact match found
        if ($campuses->isEmpty() && !empty($search)) {

            $search = strtolower(trim($search));

            $suggestions = Campus::all()
                ->map(function ($campus) use ($search) {

                    // Remove "Campus" from the name
                    $cleanName = strtolower(
                        str_replace(' campus', '', $campus->name)
                    );

                    // Compare against every word
                    $distance = collect(explode(' ', $cleanName))
                        ->map(fn($word) => levenshtein($search, $word))
                        ->min();

                    similar_text($search, $cleanName, $similarity);

                    $campus->distance = $distance;
                    $campus->similarity = $similarity;

                    return $campus;
                })
                ->filter(function ($campus) {
                    return $campus->distance <= 2
                        || $campus->similarity >= 70;
                })
                ->sort(function ($a, $b) {

                    if ($a->distance === $b->distance) {
                        return $b->similarity <=> $a->similarity;
                    }

                    return $a->distance <=> $b->distance;
                })
                ->take(3)
                ->values()
                ->map(function ($campus) {
                    unset($campus->distance);
                    unset($campus->similarity);

                    return [
                        'name' => $campus->name,
                        'code' => $campus->code,
                        'address' => $campus->address,
                    ];
                });

            if ($suggestions->isNotEmpty()) {
                return $this->response(
                    'You may have mistyped the campus name.',
                    'Campus not found. Did you mean one of these?',
                    $suggestions->toArray(),
                    404
                );
            }

            return $this->response(
                'error',
                data: ['campus' => 'Not Found'],
                statusCode: 404
            );
        }

        return $this->response(
            'success',
            'Campuses retrieved successfully.',
            $campuses->toArray(),
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
    public function store(StoreCampusRequest $request)
    {
        DB::beginTransaction();

        try {
            $campus = Campus::create($request->validated());

            DB::commit();

            CacheService::invalidate(CacheService::CAMPUSES);

            return $this->response(
                'success',
                'Campus created successfully',
                $campus->toArray(),
                201
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Campus $campus)
    {
        $this->authorize('view', $campus);

        return $this->response(
            'success',
            'Campus retrieved successfully',
            $campus->toArray(),
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campus $campus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampusRequest $request, Campus $campus)
    {
        DB::beginTransaction();

        try {
            $campus->update($request->validated());

            DB::commit();

            CacheService::invalidate(CacheService::CAMPUSES);

            return $this->response(
                'success',
                'Campus updated successfully',
                $campus->toArray(),
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus)
    {
        $this->authorize('delete', $campus);

        DB::beginTransaction();

        try {

            $campus->delete();

            DB::commit();

            CacheService::invalidate(CacheService::CAMPUSES);
            CacheService::invalidate(CacheService::DEPARTMENTS);
            CacheService::invalidate(CacheService::PROGRAMS);

            return $this->response(
                'success',
                'Campus deleted successfully',
                null,
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
