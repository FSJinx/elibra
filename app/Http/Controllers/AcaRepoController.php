<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcaRepoRequest;
use App\Http\Requests\UpdateAcaRepoRequest;
use App\Models\AcaRepo;
use App\Services\AcaRepoService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class AcaRepoController extends Controller
{
    public function __construct(private AcaRepoService $acaRepoService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $this->user();
        $filters = QueryService::filters($request);
        $filters = array_merge($filters, [
            'submitted_by' => $request->input('submitted_by') ?? null,
            'library_id' => $request->input('library_id') ?? $user->librarian->library_id ?? null,
        ]);

        $theses = AcaRepo::query()
            ->when($filters['library_id'] !== null, fn ($q) => $q->where('library_id', $filters['library_id']))
            ->when($filters['submitted_by'] !== null, fn ($q) => $q->where('submitted_by', $filters['submitted_by']))
            ->with(['language', 'program', 'submitter.user:id,last_name,first_name', 'submitter.program', 'library'])
            ->paginate();

        return $this->response(
            'success',
            'Academic repositories retrieved successfully.',
            $theses,
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
        $patron = $user->patron;
        $data = [
            ...$request->validated(),
            'submitted_by' => $patron->id,
            'program_id' => $patron->program_id,
        ];

        $acaRepo = $user->isPatron()
            ? $this->acaRepoService->submitAcaRepo($data)
            : $this->acaRepoService->create($data);

        return $this->response(
            'success',
            'Academic repository submission created successfully.',
            $acaRepo->load(['program', 'submitter.user', 'submitter.program', 'library'])->toArray(),
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AcaRepo $acaRepo)
    {
        $acaRepo->load(['language', 'program', 'submitter.user', 'submitter.program', 'library']);

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
            $acaRepo->load(['language', 'program', 'submitter.user', 'submitter.program', 'library'])->toArray(),
            200
        );
    }

    public function reject(UpdateAcaRepoRequest $request, AcaRepo $acaRepo)
    {
        $data = array_merge($request->validated(), [
            'status' => 'rejected',
            'statusRemark' => $request->input('statusRemark') ?? null,
        ]);

        $acaRepo = $this->acaRepoService->update($acaRepo, $data);

        return $this->response(
            'success',
            'Academic repository rejected successfully.',
            $acaRepo->load(['language', 'program', 'submitter.user', 'submitter.program', 'library'])->toArray(),
            200
        );
    }

    public function approve(AcaRepo $acaRepo)
    {
        $acaRepo->update([
            'status' => 'approved',
            'statusRemark' => null,
        ]);

        return $this->response(
            'success',
            'Academic repository approved successfully.',
            $acaRepo->load(['language', 'program', 'submitter.user', 'submitter.program', 'library'])->toArray(),
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
