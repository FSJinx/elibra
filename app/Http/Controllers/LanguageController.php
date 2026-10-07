<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Language;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Throwable;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::all();

        return $this->response(
            'success',
            'Languages retrieved successfully',
            $languages->toArray(),
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageRequest $request)
    {
        DB::beginTransaction();

        try {
            $language = Language::create($request->validated());

            $language->refresh();

            DB::commit();

            CacheService::invalidate(CacheService::LANGUAGES);

            return $this->response(
                'success',
                'Language created successfully',
                $language->toArray(),
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
    public function show(Language $language)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, Language $language)
    {
        DB::beginTransaction();

        try {
            $language->update($request->validated());

            DB::commit();
            CacheService::invalidate(CacheService::LANGUAGES);

            return $this->response(
                'success',
                'Language updated successfully',
                $language,
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // All under this are for review

    public function destroy(Language $language)
    {
        // $this->authorize('delete', $language);

        DB::beginTransaction();

        try {
            $language->delete();
            DB::commit();

            CacheService::invalidate(CacheService::LANGUAGES);

            return $this->response(
                'success',
                'Language deleted successfully',
                null,
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
