<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatronRequest;
use App\Http\Requests\UpdatePatronRequest;
use App\Models\Patron;
use App\Services\PatronService;

class PatronController extends Controller
{
    protected PatronService $patronService;

    public function __construct(PatronService $patronService)
    {
        $this->patronService = $patronService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Patron::with([
            'user',
            'patronType',
            'program'
        ])->get();
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
    public function store(StorePatronRequest $request)
    {
        $result = $this->patronService->create($request->validated());

        return $this->response(
            'success',
            'Patron successfully created.',
            $result,
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Patron $patron)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patron $patron)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatronRequest $request, Patron $patron)
    {
        $result = $this->patronService->update($patron, $request->validated());

        return $this->response(
            'success',
            'Patron successfully updated.',
            $result,
            200,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patron $patron)
    {
        $deleted = $this->patronService->delete($patron);

        if (! $deleted) {
            return $this->response(
                'error',
                'Patron could not be deleted.',
                null,
                500,
            );
        }

        return $this->response(
            'success',
            'Patron deleted successfully.',
            null,
            200,
        );
    }
}
