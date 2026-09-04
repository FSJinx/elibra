<?php

namespace App\Http\Controllers;

use App\Models\ItemTypeCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemTypeCategoryRequest;
use App\Http\Requests\UpdateItemTypeCategoryRequest;

class ItemTypeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemTypeCategories = ItemTypeCategory::all();

        return $this->response(
            'success',
            'Item Type Categories retrieved successfully',
            $itemTypeCategories->toArray(),
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
    public function store(StoreItemTypeCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemTypeCategory $itemTypeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemTypeCategory $itemTypeCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemTypeCategoryRequest $request, ItemTypeCategory $itemTypeCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemTypeCategory $itemTypeCategory)
    {
        //
    }
}
