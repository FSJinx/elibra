<?php

namespace App\Http\Controllers;

use App\Models\BranchSection;
use App\Http\Requests\StoreBranchSectionRequest;
use App\Http\Requests\UpdateBranchSectionRequest;
use App\Models\Branch;

class BranchSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $branch = Branch::findOrFail($request->branch_id);

        $this->authorize('create', [BranchSection::class, $branch]);

        $branchSection = BranchSection::create($request->validated());

        return $this->response(
            'success',
            'Branch Section created successfully',
            $branchSection->toArray(),
            201
        );
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchSection $branchSection)
    {
        //
    }
}
