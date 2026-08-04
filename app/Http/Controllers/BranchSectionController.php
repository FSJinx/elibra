<?php

namespace App\Http\Controllers;

use App\Models\BranchSection;
use App\Http\Requests\StoreBranchSectionRequest;
use App\Http\Requests\UpdateBranchSectionRequest;
use App\Models\Branch;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BranchSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $this->user();

            [
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
                'page' => $page,
                'per_page' => $perPage,
            ] = QueryService::filters($request);

            $campusId = $user?->campus_id;

            $branchSections = CacheService::remember(
                CacheService::BRANCH_SECTIONS,
                [
                    'campus_id' => $campusId,
                    'search' => $search,
                    'sort' => $sort,
                    'order' => $order,
                    'page' => $page,
                    'per_page' => $perPage,
                ],
                now()->addHour(),
                function () use ($campusId, $search, $sort, $order, $perPage) {

                    $allowedSortFields = [
                        'created_at',
                        'branch_id',
                        'section_id',
                    ];

                    $query = BranchSection::query()
                        ->with(['branch', 'section']);

                    // Guests can see all.
                    // Logged-in users only see records from their campus.
                    if (! is_null($campusId)) {
                        $query->whereHas('branch', function ($q) use ($campusId) {
                            $q->where('campus_id', $campusId);
                        });
                    }

                    if ($search) {
                        $query->whereHas('section', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                    }

                    if (is_array($sort) && ! empty($sort)) {
                        foreach ($sort as $field) {
                            if (in_array($field, $allowedSortFields, true)) {
                                $query->orderBy($field, $order === 'desc' ? 'desc' : 'asc');
                            }
                        }
                    } else {
                        $query->latest();
                    }

                    return $query->paginate($perPage);
                }
            );

            return $this->response(
                'success',
                'Branch Sections retrieved successfully',
                $branchSections->toArray(),
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
    public function store(StoreBranchSectionRequest $request)
    {
        DB::beginTransaction();
        try {

            $branchSection = BranchSection::create($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::BRANCH_SECTIONS);

            return $this->response(
                'success',
                'Branch Section created successfully',
                $branchSection->toArray(),
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
    public function show(BranchSection $branchSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BranchSection $branchSection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchSectionRequest $request, BranchSection $branchSection)
    {
        DB::beginTransaction();
        try {
            $branchSection->update($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::BRANCH_SECTIONS);

            return $this->response(
                'success',
                'Branch Section updated successfully',
                $branchSection->toArray(),
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
    public function destroy(BranchSection $branchSection)
    {
        $this->authorize('delete', $branchSection);

        DB::beginTransaction();
        try {
            $branchSection->delete();

            DB::commit();
            CacheService::invalidate(CacheService::BRANCH_SECTIONS);

            return $this->response(
                'success',
                'Branch Section deleted successfully',
                null,
                200
            );

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

    }
}
