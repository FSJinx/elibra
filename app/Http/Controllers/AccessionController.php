<?php

namespace App\Http\Controllers;

use App\Models\Accession;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccessionRequest;
use App\Http\Requests\UpdateAccessionRequest;
use App\Services\AccessionService;

class AccessionController extends Controller
{
    protected AccessionService $accessionService;
    public function __construct(AccessionService $accessionService)
    {
        $this->accessionService = $accessionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function store(StoreAccessionRequest $request)
    {
        $accession = $this->accessionService->create($request->validated());

        return $this->response(
            'success',
            'Accession created successfully',
            $accession->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Accession $accession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accession $accession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccessionRequest $request, Accession $accession)
    {
        $accession = $this->accessionService->update($accession, $request->validated());

        return $this->response(
            'success',
            'Accession updated successfully',
            $accession->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accession $accession)
    {
        $this->accessionService->delete($accession);

        return $this->response(
            'success',
            'Accession deleted successfully',
            [],
            200
        );
    }
}
