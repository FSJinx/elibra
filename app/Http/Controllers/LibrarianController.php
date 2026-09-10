<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibrarianRequest;
use App\Http\Requests\UpdateLibrarianRequest;
use App\Models\Librarian;
use App\Services\LibrarianService;

class LibrarianController extends Controller
{
    protected LibrarianService $librarianService;

    public function __construct(LibrarianService $librarianService)
    {
        $this->librarianService = $librarianService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Librarian::with([
            'user',
            'branch',
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
    public function store(StoreLibrarianRequest $request)
    {
        $result = $this->librarianService->create($request->validated());

        return $this->response(
            'success',
            'Librarian successfully created.',
            $result,
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Librarian $librarian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Librarian $librarian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibrarianRequest $request, Librarian $librarian)
    {
        $result = $this->librarianService->update($librarian, $request->validated());

        return $this->response(
            'success',
            'Librarian successfully updated.',
            $result,
            200,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Librarian $librarian)
    {
        $deleted = $this->librarianService->delete($librarian);

        if (! $deleted) {
            return $this->response(
                'error',
                'Librarian could not be deleted.',
                null,
                500,
            );
        }

        return $this->response(
            'success',
            'Librarian deleted successfully.',
            null,
            200,
        );
    }
}
