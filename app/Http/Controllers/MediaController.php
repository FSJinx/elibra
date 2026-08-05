<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Services\CacheService;
use App\Services\MediaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MediaController extends Controller
{
    public function __construct(
        private MediaService $mediaService
    ) {}
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
    public function upload(StoreMediaRequest $request)
    {
        DB::beginTransaction();
        try {

            $media = $this->mediaService->store(
                $request->file('image'),
                $request->image_type
            );

            DB::commit();

            CacheService::invalidate(CacheService::MEDIA);

            return $this->response(
                'success',
                'Media uploaded successfully',
                $media->toArray(),
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
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaRequest $request, Media $media)
    {
        DB::beginTransaction();
        try {
            $media = $this->mediaService->replaceFile($media, $request->file('image'));

            DB::commit();
            CacheService::invalidate(CacheService::MEDIA);

            return $this->response(
                'success',
                'Media updated successfully',
                $media->toArray(),
                200
            );
        } catch (Throwable $e){
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);
        DB::beginTransaction();
        try{
            $$this ->mediaService->delete($media);

            DB::commit();
            CacheService::invalidate(CacheService::MEDIA);

            return $this->response(
                'success',
                'Media deleted successfully',
                [],
                200
            );

        } catch (Throwable $e){ 
            DB::rollBack();

            throw $e;
        }
    }
}
