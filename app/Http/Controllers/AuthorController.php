<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use App\Models\Item;
use App\Services\AuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    protected AuthorService $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = trim((string) $request->input('query'));

        $authors = Author::query()
            ->when($query !== '', function ($q) use ($query) {
                $search = "%{$query}%";

                $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', $search)
                        ->orWhere('last_name', 'LIKE', $search);
                });
            })
            ->get();

        if ($authors->isEmpty()) {
            return $this->response('error', 'No matching authors. Try adding one.', []);
        }

        return $this->response(
            'success',
            'Authors retrieved successfully',
            $authors->toArray(),
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

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
    public function show(Request $request)
    {
        $query = trim((string) $request->input('query'));

        $item = Item::query();
        

        if ($query === '') {
            return $this->response('error', 'Please enter an author name.', [], 422);
        }

        $authors = Author::query()
            ->where(function ($authorQuery) use ($query) {
                $search = "%{$query}%";

                $authorQuery
                    ->where('first_name', 'LIKE', $search)
                    ->orWhere('last_name', 'LIKE', $search);
            })
            ->get();

        if ($authors->isEmpty()) {
            return $this->response('error', 'No matching authors. Try adding one.', [], 404);
        }

        return $this->response(
            'success',
            'Authors retrieved successfully',
            $authors->toArray(),
            200
        );

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

        if (! $deleted) {
            return $this->response(
                'error',
                'Unable to delete author at this time.',
                [],
                500
            );
        }

        return $this->response(
            'success',
            'Author deleted successfully',
            null,
            200
        );
    }
}
