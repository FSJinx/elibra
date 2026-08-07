<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Serial;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SerialService
{
public function create(array $data): Serial
{
    $serial = DB::transaction(function () use ($data){
        $this->saveElectronicFile($data);

        $item = Item::create(
            Arr::only($data, [
                'title', 
                'subtitle', 
                'description', 
                'call_number',
                'language',
                'publication_year',
                'keywords',
                'electronic_file',
                'branch_id'
            ])
        );

        return $item->serial()->create(
            arr::only($data, [
                'isbn_issn',
                'volume',
                'issue',
                'pages',
                'doi',
                'item_id'
            ])
        );
        
    });
    CacheService::invalidate(CacheService::SERIALS);

    return $serial->load('item');
}

public function update(Serial $serial, array $data): Serial
{
    $oldFile = $serial->item->electronic_file;

    $serial = DB::transaction(function () use ($serial, $data){

        if (!$this->saveElectronicFile($data)) {
            unset($data['electronic_file']);
        }
              
        $serial->item->update(
            Arr::only($data, [
                'title', 
                'subtitle', 
                'description', 
                'call_number',
                'language',
                'publication_year',
                'keywords',
                'electronic_file',
                'branch_id'
            ])
        );

        return $serial->update(
            arr::only($data, [
                'isbn_issn',
                'volume',
                'issue',
                'pages',
                'doi',
                'item_id'
            ])
        );

        return $serial->fresh(['item']);
    });
    if (
        isset($data['electronic_file']) &&
        $oldFile &&
        $oldFile !== $data['electronic_file']
    ) {
        Storage::disk('public')->delete($oldFile);
    }
    CacheService::invalidate(CacheService::SERIALS);
    return $serial;
}

Public function delete(Serial $serial): bool
{
    $deleted = DB::transaction(function () use ($serial){
        $serial->item->delete();
        $serial->delete();
    });

    if($deleted){
        CacheService::invalidate(CacheService::ACADEMICS);
    }

    return $deleted;
}

private function saveElectronicFile(array &$data): bool
{
    if (
        isset($data['electronic_file']) &&
        $data['electronic_file'] instanceof UploadedFile
    ) {
        $data['electronic_file'] = $data['electronic_file']
            ->store('item/serials', 'public');

        return true;
    }

    return false;
}
}