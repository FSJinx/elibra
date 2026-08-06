<?php
namespace App\Services;

use App\Models\Academic;
use App\Models\Item;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AcademicService
{
    public function create(array $data): Academic
    {
        $academic = DB::transaction(function () use ($data){

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

        return $academic;
    }

    public function update(Academic $academic, array $data): Academic
    {
        $academic = DB::transaction(function () use ($academic, $data){
            
            $academic->item->update(
                Arr::only($data, [
                    'title', 
                    'subtitle', 
                    'description', 
                    'keywords',
                    'branch_id'
                ])
            );

            $academic->update(
                Arr::only($data, [
                    'call_number',
                    'language',
                    'category',
                    'publication_year',
                    'subjects',
                    'department_id'
                ])
            );
            
            // Refresh the academic model to get the latest data from the database
            return $academic->refresh(); 
        });
        CacheService::invalidate(CacheService::ACADEMICS);
        return $academic;
    }

    public function delete(Academic $academic): void
    {
        DB::transaction(function () use ($academic){
            $academic->item->delete();
            $academic->delete();
        });
        
        CacheService::invalidate(CacheService::ACADEMICS);
    }
}