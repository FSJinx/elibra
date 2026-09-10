<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcquisitionLinesRequest;
use App\Http\Requests\UpdateAcquisitionLinesRequest;
use App\Models\AcquisitionLines;
use App\Services\AcquisitionLinesService;
use Illuminate\Support\Facades\Cache;

class AcquisitionLinesController extends Controller
{
    protected AcquisitionLinesService $acquisitionLineService;

    public function __construct(AcquisitionLinesService $acquisitionLineService)
    {
        $this->acquisitionLineService = $acquisitionLineService;
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
    public function store(StoreAcquisitionLinesRequest $request)
    {
        $acquisitionLine = $this->acquisitionLineService->create($request->validated());

        return $this->response(
            'success',
            'Acquisition Line created successfully',
            $acquisitionLine->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Cache::remember(
            'acquisition-lines:${id}',
            now()->addHour(),
            fn () => AcquisitionLines::with('items')->where('acquisition_id', $id)->get()
        );

        return $this->response(data: $data->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcquisitionLines $acquisitionLines)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcquisitionLinesRequest $request, AcquisitionLines $acquisitionLine)
    {
        $acquisitionLine = $this->acquisitionLineService->update(
            $acquisitionLine,
            $request->validated()
        );

        return $this->response(
            'success',
            'Acquisition updated successfully',
            $acquisitionLine->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcquisitionLines $acquisitionLine)
    {
        $this->authorize('delete', $acquisitionLine);

        $deleted = $this->acquisitionLineService->delete($acquisitionLine);

        if (! $deleted) {
            return $this->response(
                'Error',
                'The selected Acquisition Line record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Acquisition Line record deleted successfully',
            null,
            200
        );

    }
}
