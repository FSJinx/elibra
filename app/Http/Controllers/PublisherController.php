<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Http\Requests\StorePublisherRequest;
use App\Http\Requests\UpdatePublisherRequest;
use App\Services\PublisherService;

class PublisherController extends Controller
{
    protected PublisherService $publisherService;

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publisher = Publisher::all();

        return $this->response(
            'success',
            'Publisher retriieved successfully',
            $publisher->toArray(),
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
    public function store(StorePublisherRequest $request)
    {
        $publisher = $this->publisherService->create($request->validated());

        return $this->response(
            'success',
            'Publisher created successfully',
            $publisher->toArray(),
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublisherRequest $request, Publisher $publisher)
    {
        $publisher = $this->publisherService->update($publisher, $request->validated());

        return $this->response(
            'success',
            'Publisher updated successfully',
            $publisher->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        $deleted = $this->publisherService->delete($publisher);
        
        if (! $deleted) {
            return $this->response(
                'error',
                'Unable to delete publisher at this time.',
                [],
                500
            );
        }

        return $this->response(
            'success',
            'Publisher deleted successfully.',
            [],
            200
        );
    }
}
