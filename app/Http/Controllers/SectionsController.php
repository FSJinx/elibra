<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionsRequest;
use App\Http\Requests\UpdateSectionsRequest;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Sections::get('name');

        return $this->response(
            'success',
            'Sections retrieved successfully',
            $sections->toArray(),
            200,
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
    public function store(StoreSectionsRequest $request)
    {
        $this->authorize('create', Sections::class);

        $section = Sections::create($request->validated());

        return $this->response(
            'success',
            'Section created successfully',
            $section->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionsRequest $request, Sections $section)
    {
        $this->authorize('update', $section);

        $section->update($request->validated());
        

        return $this->response(
            'success',
            'Section updated successfully',
            $section->toArray(),
            200,
        );  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sections $section)
    {
        $this->authorize('delete', $section);

        $section->delete();

        return $this->response(
            'success',
            'Section deleted successfully',
            null,
            200
        );

    }
}
