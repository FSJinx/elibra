<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Throwable;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->user();

        // if user os authenticated and a library admin
        if ($user && $user->isAdmin()) {
            $campusId = $user->campus_id;

            // para ma return lahat ng branch based sa users campus
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
        DB::beginTransaction();
        try {

            $branch = Branch::create($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::BRANCHES);

            return $this->response(
                'success',
                'Branch created successfully',
                $branch->toArray(),
                201
            );

        } catch(Throwable $e) {
            DB::rollBack();

            throw $e;
        }
        
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
        DB::beginTransaction();
        try{
            $branch->update($request->validated());

            DB::commit();

            CacheService::invalidate(CacheService::BRANCHES);

            return $this->response(
                'success',
                'Branch updated successfully',
                $branch->toArray(),
                200
            );
        } catch (Throwable $e){
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        DB::beginTransaction();
        try{

            $branch->delete();
            DB::commit();

            CacheService::invalidate(CacheService::BRANCHES);
            CacheService::invalidate(CacheService::SECTIONS);
            CacheService::invalidate(CacheService::BRANCH_SECTIONS);

            return $this->response(
                'success',
                'Branch deleted successfully',
                null,
                200
            );
        } catch(Throwable $e){
            DB::rollBack();
            throw $e;
        }
    

    }
}
