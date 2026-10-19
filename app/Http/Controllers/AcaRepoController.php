<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcaRepoRequest;
use App\Http\Requests\UpdateAcaRepoRequest;
use App\Models\AcaRepo;
use App\Services\AcaRepoService;

class AcaRepoController extends Controller
{
    public function __construct(private AcaRepoService $acaRepoService)
    {
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
    public function store(StoreAcaRepoRequest $request)
    {
        $acaRepo = $this->acaRepoService->submitAcaRepo([
            ...$request->validated(),
            'program_id' => $request->user()->patron->program_id,
        ]);

        return $this->response(
            'success',
            'Academic repository submission created successfully.',
            $acaRepo->toArray(),
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AcaRepo $acaRepo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcaRepo $acaRepo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcaRepoRequest $request, AcaRepo $acaRepo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcaRepo $acaRepo)
    {
        //
    }
}
