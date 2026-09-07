<?php

namespace App\Http\Controllers;

use App\Models\Authorship;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorshipRequest;
use App\Http\Requests\UpdateAuthorshipRequest;

class AuthorshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authorships = Authorship::all();

        return $this->response(
            'success',
            'Item Type Categories retrieved successfully',
            $authorships->toArray(),
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
    public function store(StoreAuthorshipRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Authorship $authorship)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Authorship $authorship)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorshipRequest $request, Authorship $authorship)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Authorship $authorship)
    {
        //
    }
}
