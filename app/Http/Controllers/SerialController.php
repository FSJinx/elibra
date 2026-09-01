<?php

namespace App\Http\Controllers;

use App\Models\Serial;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSerialRequest;
use App\Http\Requests\UpdateSerialRequest;
use App\Services\QueryService;
use App\Services\SerialService;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $serials = $this->serialService->index($filters);

        if($serials->isEmpty()){
            return $this->response(
                'success',
                'No serial record found.',
                [],
                200
            );
        }

        return $this->response(
            'success',
            'Serials retrieved successfully',
            $serials,
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
    public function destroy(int $serialId)
    {
        $serial = Serial::find($serialId);

        if(!$serial) {
            return $this->response(
                'Error',
                'The selected serial record could not be found.',
                null,
                404
            );
        }

        $this->authorize('delete', $serial);

        $deleted = $this->serialService->delete($serial);

        if(!$deleted) {
            return $this->response(
                'Error',
                'The selected serial record could not be found.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Serial deleted successfully',
            null,
            200
        );
    }
}
