<?php

namespace App\Http\Controllers;

use App\Models\Sections;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionsRequest;
use App\Http\Requests\UpdateSectionsRequest;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
                'data' => []
            ], 401);
        }

        $sections = CacheService::remember(
            CacheService::SECTIONS,
            [
                'is_super_admin' => $user->isSuperAdmin(),
            ],
            now()->addHour(),
            function () use ($user) {

                if ($user->isSuperAdmin()) {
                    return Sections::all();
                }

                return Sections::select([
                    'name',
                    'created_at',
                ])->get();
            }
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Sections retrieved successfully',
            'data' => $sections,
        ], 200);
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
        DB::beginTransaction();
        try {
            $section = Sections::create($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::SECTIONS);

            return $this->response(
                'success',
                'Section created successfully',
                $section->toArray(),
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
        DB::beginTransaction();
        try {
            $section->update($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::SECTIONS);

            return $this->response(
                'success',
                'Section updated successfully',
                $section->toArray(),
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
    public function destroy(Sections $section)
    {
        $this->authorize('delete', $section);

        DB::beginTransaction();
        try {
            $section->delete();

            DB::commit();
            CacheService::invalidate(CacheService::SECTIONS);

            return $this->response(
                'success',
                'Section deleted successfully',
                null,
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

    }
}
