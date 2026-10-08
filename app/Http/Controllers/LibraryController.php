<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibraryRequest;
use App\Http\Requests\UpdateLibraryRequest;
use App\Models\Library;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Throwable;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $library = Library::query();

        return $this->response(
            data: $library->get()
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
    public function store(StoreLibraryRequest $request)
    {
        DB::beginTransaction();
        try {
            $library = Library::create($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::LIBRARY);

            return $this->response(
                'success',
                'Library created successfully',
                $library->toArray(),
                201
            );

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Library $library)
    {
        return $this->response(data: $library->load(['campus', 'head']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Library $library)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibraryRequest $request, Library $library)
    {
        DB::beginTransaction();
        try {
            $library->update($request->validated());

            DB::commit();

            return $this->response(
                'success',
                'Library updated successfully',
                $library->toArray(),
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Library $library)
    {
        $this->authorize('delete', $library);

        DB::beginTransaction();
        try {

            $library->delete();
            $library->sections()->delete();

            DB::commit();

            CacheService::invalidate(CacheService::LIBRARY);

            return $this->response(
                'success',
                'Library deleted successfully',
                null,
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
