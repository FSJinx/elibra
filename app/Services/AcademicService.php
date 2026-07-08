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
        return DB::transaction(function () use ($data){

            $item = Item::create(
                Arr::only($data, [
                    'title', 
                    'subtitle', 
                    'description', 
                    'keywords'
                ])
            );

            return $item->academic()->create(
                Arr::only($data, [
                    'call_number', 
                    'language', 
                    'category', 
                    'publication_year', 
                    'subjects',
                    'department_id'
                ])
            );

        });
    }
}