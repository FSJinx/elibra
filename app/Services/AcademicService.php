<?php
namespace App\Services;

use App\Models\Academic;
use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AcademicService
{
    public function create(array $data): Academic
    {
        $academic = DB::transaction(function () use ($data){

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

            return $item->academic()->create(
                Arr::only($data, [
                    'category', 
                    'subjects',
                    'doi',
                    'department_id'
                ])
            );

        });

        CacheService::invalidate(CacheService::ACADEMICS);

        return $academic->load('item');
    }

    public function update(Academic $academic, array $data): Academic
    {
        $oldFile = $academic->item->electronic_file;

        $academic = DB::transaction(function () use ($academic, $data){

            if (!$this->saveElectronicFile($data)) {
                unset($data['electronic_file']);
            }
                
            $academic->item->update(
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

            $academic->update(
                Arr::only($data, [
                    'category', 
                    'subjects',
                    'doi',
                    'department_id'
                ])
            );
            
            // Refresh the academic model to get the latest data from the database
            return $academic->fresh(['item']);
        });

         // Delete the old file only after the database update succeeds
        if (
            isset($data['electronic_file']) &&
            $oldFile &&
            $oldFile !== $data['electronic_file']
        ) {
            Storage::disk('public')->delete($oldFile);
        }

        CacheService::invalidate(CacheService::ACADEMICS);
        return $academic;
    }

    public function delete(Academic $academic): bool
    {
        $deleted = DB::transaction(function () use ($academic){
            $academic->item->delete();
            $academic->delete();
        });
        
        if ($deleted) {
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
                ->store('item/academics', 'public');

            return true;
        }

        return false;
    }
}