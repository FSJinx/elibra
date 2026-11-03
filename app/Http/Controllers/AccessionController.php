<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccessionRequest;
use App\Http\Requests\UpdateAccessionRequest;
use App\Models\Accession;
use App\Services\AccessionService;
use App\Services\QueryService;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $filters = array_merge($filters, [
            'item_id' => $request->input('item_id'),
        ]);

        $patron = Accession::query()->with(['item.itemType', 'item.coverMedia'])
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where('accession_number', 'LIKE', "%$s%"))
            ->when($filters['item_id'] ?? null, fn ($q, $s) => $q->where('item_id', $s))
            ->paginate(
                perPage: null
            );

        return $this->response(
            message: $patron->isEmpty() ? 'No patrons found' : 'Patrons found',
            data: $patron
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
    public function store(StoreAccessionRequest $request)
    {
        $data = array_merge($request->validated(), [
            'quantity' => $request->input('quantity'),
        ]);
        $accession = $this->accessionService->create($data);

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
