<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use App\Models\Item;
use App\Services\AcademicService;
use App\Http\Requests\StoreAcademicRequest;
use App\Http\Requests\UpdateAcademicRequest;


class AcademicController extends Controller
{
    // inject the AcademicService into the controller
    protected AcademicService $academicService;
    // using constructor injection to inject the AcademicService into the controller
    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

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
    public function store(StoreAcademicRequest $request)
    {        
        // Authorize the user through the policy
        $this->authorize('create', Academic::class);
        
        // Use the AcademicService to create the academic resource
        $academic = $this->academicService->create($request->validated());

        return $this->response(
            'success', 
            'Academic created successfully', 
            $academic->toArray(), 
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Academic $academic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Academic $academic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademicRequest $request, Academic $academic)
    {
        $this->authorize('update', $academic);

        $academic = $this->academicService->update($academic, $request->validated());

        return $this->response(
            'success', 
            'Academic updated successfully', 
            $academic->toArray(), 
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Academic $academic)
    {
        $this->authorize('delete', $academic);

        $this->academicService->delete($academic);

        return $this->response(
            'success',
            'Academic deleted successfully',
            [],
            200
        );
    }
}
