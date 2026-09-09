<?php

namespace App\Http\Controllers;

use App\Models\Acquisition;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcquisitionRequest;
use App\Http\Requests\UpdateAcquisitionRequest;
use App\Services\AcquisitionService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class AcquisitionController extends Controller
{
    protected AcquisitionService $acquisitionService;

    public function __construct(AcquisitionService $acquisitionService)
    {
        $this->acquisitionService = $acquisitionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $acquisition = $this->acquisitionService->index($filters);

        if($acquisition->isEmpty()){
            return $this->response(
                'success',
                'No acquisition record found.',
                [],
                200
            );
        }
        return $this->response(
            'success',
            'Acquisitions retrieved successfully.',
            $acquisition->toArray(),
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
    public function store(StoreAcquisitionRequest $request)
    {
        $acquisition = $this->acquisitionService->create($request->validated());

        return $this->response( 
            'success', 
            'Acquisition created successfully', 
            $acquisition->toArray(),            
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(UpdateAcquisitionRequest $request, Acquisition $acquisition)
    {
//
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acquisition $acquisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcquisitionRequest $request, Acquisition $acquisition)
    {
        $acquisition = $this->acquisitionService->update(
                $acquisition, 
                $request->validated()
        );

        return $this->response(
            'success', 
            'Acquisition updated successfully', 
            $acquisition->toArray(), 
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acquisition $acquisition)
    {
        $this->authorize('delete', $acquisition);

        $deleted = $this->acquisitionService->delete($acquisition);

        if (!$deleted) {
            return $this->response(
                'Error',
                'The selected Acquisition record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Acquisition record deleted successfully',
            null,
            200
        );

    }
}
