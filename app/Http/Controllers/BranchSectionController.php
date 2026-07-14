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
        $user = $this->user();

        // if user os authenticated and a library admin
        if ($user && $user->hasPrimaryRole('library admin')) {
            $campusId = $user->librarian->branch->campus_id;

            $branchSections = BranchSection::where('branch_id', $campusId)->get();

        } else {
            //returns all the branch sections
            $branchSections = BranchSection::get([
                'section_head_id',
                'branch_id',
                'section_id'
            ]);
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

        $branch = $request->filled('branch_id')  // This will check if the request contains a new  branch_id
                ? Branch::findOrFail($request->branch_id) // Kung yes, get that branch from db
                : $branchSection->branch; //if not, use the BranchSection's current branch.

        $this->authorize('update', [$branchSection, $branch]); // gamit array if multiple parameter galing policy

        $branchSection->update($request->validated());

        return $this->response(
            'success',
            'Branch Section updated successfully',
            $branchSection->toArray(),
            200
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchSection $branchSection)
    {
        $this->authorize('delete', $branchSection);

        $branchSection->delete();

        return $this->response(
            'success',
            'Branch Section deleted successfully.',
            [],
            200
        );

    }
}
