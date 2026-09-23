<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCirculationRequest;
use App\Http\Requests\UpdateCirculationRequest;
use App\Models\Circulation;
use App\Services\CirculationService;

class CirculationController extends Controller
{
    public function __construct(
        protected CirculationService $circulationService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $circulations = Circulation::with([
            'processedBy',
            'patron',
            'accession',
            'loanMode',
            'returnReceivedBy',
        ])->get();

        return $this->response(
            'success',
            'Circulations retrieved successfully.',
            $circulations->toArray(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCirculationRequest $request)
    {
        $circulation = $this->circulationService->create($request->validated());

        return $this->response(
            'success',
            'Circulation created successfully.',
            $circulation->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Circulation $circulation)
    {
        $circulation->load([
            'processedBy',
            'patron',
            'accession',
            'loanMode',
            'returnReceivedBy',
        ]);

        return $this->response(
            'success',
            'Circulation retrieved successfully.',
            $circulation->toArray(),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCirculationRequest $request, Circulation $circulation)
    {
        $updated = $this->circulationService->update($circulation, $request->validated());

        return $this->response(
            'success',
            'Circulation updated successfully.',
            $updated->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Circulation $circulation)
    {
        $deleted = $this->circulationService->delete($circulation);

        if (! $deleted) {
            return $this->response(
                'error',
                'The selected circulation record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Circulation record deleted successfully.',
            null,
            200
        );
    }
}
