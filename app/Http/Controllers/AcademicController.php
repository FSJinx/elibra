<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use App\Models\Item;
use App\Services\AcademicService;
use App\Http\Requests\StoreAcademicRequest;
use App\Http\Requests\UpdateAcademicRequest;
use Illuminate\Support\Facades\DB;
use Throwable;

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
        //$this ->authorize('viewAny', Academic::class);

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
        $academic = $this->academicService->create($request->validated());

        return $this->response( 
            'success', 
            'Academic created successfully', 
            $academic->load('item')->toArray(),            
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

        $academic = $this->academicService->update(
                $academic, 
                $request->validated()
        );

        return $this->response(
            'success', 
            'Academic updated successfully', 
            $academic->load('item')->toArray(), 
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $academicId)
    {
        $academic = Academic::find($academicId);

        if (!$academic) {
            return $this->response(
                'Error',
                'The selected Academic record could not be found.',
                null,
                404
            );
        }

        $this->authorize('delete', $academic);

        $deleted = $this->academicService->delete($academic);

        if (!$deleted) {
            return $this->response(
                'Error',
                'The selected Academic record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Academic deleted successfully',
            null,
            200
        );
    }
}
