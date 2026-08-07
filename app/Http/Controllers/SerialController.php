<?php

namespace App\Http\Controllers;

use App\Models\Serial;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSerialRequest;
use App\Http\Requests\UpdateSerialRequest;
use App\Services\SerialService;

class SerialController extends Controller
{
    protected SerialService $serialService;
    public function __construct(SerialService $serialService)
    {
        $this->serialService = $serialService;
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
    public function store(StoreSerialRequest $request)
    {
        $serial = $this->serialService->create($request->validated());

        return $this->response(
            'success', 
            'Serial created successfully', 
            $serial->load('item')->toArray(),            
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Serial $serial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Serial $serial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSerialRequest $request, Serial $serial)
    {
        $serial = $this->serialService->update(
            $serial,
            $request->validated()
        );

        return $this->response(
            'success',
            'Serial updated successfully',
            $serial->load('item')->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Serial $serial)
    {
        $this->authorize('delete', $serial);

        $this->serialService->delete($serial);

        return $this->response(
            'success',
            'Serial deleted successfully',
            [],
            200
        );
    }
}
