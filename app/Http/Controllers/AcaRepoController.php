<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcaRepoRequest;
use App\Http\Requests\UpdateAcaRepoRequest;
use App\Models\AcaRepo;
use App\Services\AcaRepoService;

class AcaRepoController extends Controller
{
    public function __construct(private AcaRepoService $acaRepoService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acaRepos = AcaRepo::with(['language', 'program', 'submitter', 'library'])->latest()->get();

        return $this->response(
            'success',
            'Academic repositories retrieved successfully.',
            $acaRepos->toArray(),
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
    public function store(StoreAcaRepoRequest $request)
    {
        $user = $request->user();
        $data = [
            ...$request->validated(),
            'submitted_by' => $user->id,
        ];

        if ($user->isPatron()) {
            $patron = $user->patron;
            $data['program_id'] = $patron->program_id;
            $acaRepo = $this->acaRepoService->submitAcaRepo($data);
        } else {
            $acaRepo = $this->acaRepoService->create($data);
        }

        return $this->response(
            'success',
            'Academic repository submission created successfully.',
            $acaRepo->load(['submitter', 'library'])->toArray(),
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AcaRepo $acaRepo)
    {
        $acaRepo->load(['language', 'program', 'submitter', 'library']);

        return $this->response(
            'success',
            'Academic repository retrieved successfully.',
            $acaRepo->toArray(),
            200
        );
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
        $acaRepo = $this->acaRepoService->update($acaRepo, $request->validated());

        return $this->response(
            'success',
            'Academic repository updated successfully.',
            $acaRepo->load(['language', 'program', 'submitter', 'library'])->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcaRepo $acaRepo)
    {
        if (! $this->acaRepoService->delete($acaRepo)) {
            return $this->response(
                'error',
                'The academic repository could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Academic repository deleted successfully.',
            null,
            200
        );
    }
}
