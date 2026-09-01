<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Services\BookService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected BookService $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $books = $this->bookService->index($filters);

        if($books->isEmpty()){
            return $this->response(
                'success',
                'No book record found.',
                [],
                200
            );
        }

        return $this->response(
            'success',
            'Books retrieved successfully',
            $books,
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
    public function store(StoreBookRequest $request)
    {
        $book = $this->bookService->create($request->validated());

        return $this->response(
            'success',
            'Book created successfully',
            $book->load('item')->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $books)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $books)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book = $this->bookService->update(
            $book,
            $request->validated()
        );

        return $this->response(
            'success',
            'Book updated successfully',
            $book->load('item')->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $bookId)
    {
        $book = Book::find($bookId);

        if(!$book) {
            return $this->response(
                'Error',
                'The selected Book record could not be found.',
                null,
                404
            );
        }

        $this->authorize('delete', $book);

        $deleted = $this->bookService->delete($book);

        if(!$deleted) {
            return $this->response(
                'Error',
                'The selected Book record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Book deleted successfully',
            null,
            200
        );  
    }
}
