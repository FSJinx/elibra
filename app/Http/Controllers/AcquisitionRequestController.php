<?php

namespace App\Http\Controllers;

use App\Models\AcquisitionRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcquisitionRequestRequest;
use App\Http\Requests\UpdateAcquisitionRequestRequest;
use App\Services\AcquisitionRequestService;

class AcquisitionRequestController extends Controller
{
    protected AcquisitionRequestService $acquisitionRequestService;

    public function __construct(AcquisitionRequestService $acquisitionRequestService)
    {
        $this->acquisitionRequestService = $acquisitionRequestService;
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
    public function store(StoreAcquisitionRequestRequest $request)
    {
        $acquisitionRequest = $this->acquisitionRequestService->create(
            $request->validated(),
            $request->user()->id
        );

        return $this->response( 
            'success', 
            'Acquisition Request created successfully', 
            $acquisitionRequest->toArray(),            
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AcquisitionRequest $acquisitionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcquisitionRequest $acquisitionRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcquisitionRequestRequest $request, AcquisitionRequest $acquisitionRequest)
    {
        $acquisitionRequest = $this->acquisitionRequestService->update(
            $acquisitionRequest,
            $request->validated()
        );

        return $this->response(
            'success', 
            'Acquisition Request updated successfully', 
            $acquisitionRequest->toArray(), 
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcquisitionRequest $acquisitionRequest)
    {
        $this->authorize('delete', $acquisitionRequest);

        $deleted = $this->acquisitionRequestService->delete($acquisitionRequest);

        if (!$deleted) {
            return $this->response(
                'Error',
                'The selected Acquisition Request record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Acquisition Request record deleted successfully',
            null,
            200
        );
    }
}
