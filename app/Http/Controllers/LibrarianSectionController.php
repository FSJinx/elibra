<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLibrarianSectionRequest;
use App\Http\Requests\UpdateLibrarianSectionRequest;
use App\Models\LibrarianSection;

class LibrarianSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return LibrarianSection::all();
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
    public function store(StoreLibrarianSectionRequest $request)
    {
        return LibrarianSection::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LibrarianSection $librarianSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LibrarianSection $librarianSection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibrarianSectionRequest $request, LibrarianSection $librarianSection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LibrarianSection $librarianSection)
    {
        //
    }
}
