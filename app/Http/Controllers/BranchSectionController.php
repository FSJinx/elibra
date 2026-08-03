<?php

namespace App\Http\Controllers;

use App\Models\BranchSection;
use App\Http\Requests\StoreBranchSectionRequest;
use App\Http\Requests\UpdateBranchSectionRequest;
use App\Models\Branch;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Throwable;

class BranchSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->user();

        if ($user && $user->isAdmin()) {
            $branchSections = BranchSection::whereHas('branch', function ($query) use ($user) {
                    $query->where('campus_id', $user->campus_id);
                })->get();
        } elseif ($user) {
            $branchSections = BranchSection::whereHas('branch', function ($query) use ($user) {
                    $query->where('campus_id', $user->campus_id);
                })->get();
        } else {
            $branchSections = BranchSection::all(); 
        }

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

        // $branch = $request->filled('branch_id')  // This will check if the request contains a new  branch_id
        //         ? Branch::findOrFail($request->branch_id) // Kung yes, get that branch from db
        //         : $branchSection->branch; //if not, use the BranchSection's current branch.

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
