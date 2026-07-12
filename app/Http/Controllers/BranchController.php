<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;

class BranchController extends Controller
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

            $branches = Branch::where('campus_id', $campusId)->get();
        } else {
            //returns all the branch
            $branches = Branch::get([
                'name', 
                'contact_info', 
                'email', 
                'opening_hour', 
                'closing_hour',
                
                'logo_id',
                'branch_head_id',
                'campus_id'
            ]);
        }

        return $this->response(
            'success',
            'Branches retrieved successfully',
            $branches->toArray(),
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
    public function store(StoreBranchRequest $request)
    {
        $this->authorize('create', Branch::class);

        $branch = Branch::create($request->validated());

        return $this->response(
            'success',
            'Branch created successfully',
            $branch->toArray(),
            201
        );
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $branch->update($request->validated());

        return $this->response(
            'success',
            'Branch updated successfully',
            $branch->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);
        
        $branch->delete();

        return $this->response(
            'success',
            'Branch deleted successfully',
            null,
            200
        );

    }
}
