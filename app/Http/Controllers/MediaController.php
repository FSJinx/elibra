<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Throwable;

class MediaController extends Controller
{
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
            $file = $request->file('image');
            $path = $file->store('media','public');

            $media = Media::create([
                'file_name'  => $file->getClientOriginalName(),
                'file_path'  => $path,
                'mime_type'  => $file->getMimeType(),
                'file_size'  => $file->getSize(),
                'image_type' => $request->image_type,
            ]);

            DB::commit();
            CacheService::invalidate(CacheService::MEDIA);

            return $this->response(
                'success',
                'Media uploaded successfully',
                $media->toArray(),
                201
            );
        } catch (Throwable $e){
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
            $media->update($request->validated());

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
            $media->delete();

            DB::commit();
            CacheService::invalidate(CacheService::MEDIA);

            return $this->response(
                'success',
                'Media deleted successfully',
                $media->toArray(),
                200
            );

        } catch (Throwable $e){ 
            DB::rollBack();

            throw $e;
        }
    }
}
