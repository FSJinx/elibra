<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Services\AuthorService;

class AuthorController extends Controller
{
    protected AuthorService $authorService;

    public function __construct(AuthorService $authorService )
    {
        $this->authorService = $authorService;
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
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = $this->authorService->create($request->validated());

        return $this->response(
            'success',
            'Author retrieved successfully',
            $author->toArray(),
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author = $this->authorService->update(
            $author,
            $request->validated()
        );

        return $this->response(
            'success', 
            'Author updated successfully', 
            $author->toArray(), 
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $deleted = $this->authorService->delete($author);

        return $this->response(
            'success',
            'Author deleted successfully',
            null,
            200
        );
    }
}
