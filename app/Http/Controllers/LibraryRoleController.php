<?php

namespace App\Http\Controllers;

use App\Models\LibraryRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLibraryRoleRequest;
use App\Http\Requests\UpdateLibraryRoleRequest;

class LibraryRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return LibraryRole::all();
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
    public function store(StoreLibraryRoleRequest $request)
    {
        return LibraryRole::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LibraryRole $libraryRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LibraryRole $libraryRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibraryRoleRequest $request, LibraryRole $libraryRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LibraryRole $libraryRole)
    {
        //
    }
}
