<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcquisitionRequest;
use App\Http\Requests\UpdateAcquisitionRequest;
use App\Models\Acquisition;
use App\Services\AcquisitionService;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        $acquisitions = $this->acquisitionService->index($filters);

        return $this->response(
            'success',
            $acquisitions->isEmpty()
                ? 'No acquisition record found.'
                : 'Acquisitions retrieved successfully.',
            $acquisitions->toArray(),
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
        $data = array_merge($request->validated(), [
            'receiver_user_id' => $request->input('receiver_user_id') ?? $this->user()->id,
        ]);

        $acquisition = $this->acquisitionService->create($data);

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
    public function show(string $id)
    {
        $data = Cache::remember(
            "acquisition-show:$id",
            now()->addHour(),
            fn () => Acquisition::with(['receiver', 'acquisitionRequest'])->find($id)
        );

        if (! $data) {
            return $this->response(
                'error',
                'Acquisition not found.',
                null,
                404
            );
        }

        return $this->response(data: $data->toArray());
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

        if (! $deleted) {
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
