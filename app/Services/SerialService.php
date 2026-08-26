<?php

namespace App\Services;

use App\Jobs\IndexCatalogItemJob;
use App\Models\Item;
use App\Models\Serial;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SerialService
{

    public function index(array $filters)
    {
        return CacheService::remember(
            CacheService::SERIALS,
            $filters,
            now()->addMinutes(10),
            function () use ($filters) {

                $query = Serial::query()
                    ->with('item');

                if ($filters['search'] !== '') {
                    $search = $filters['search'];

                    $query->where(function ($q) use ($search) {
                        $q->where('isbn_issn', 'like', "%{$search}%")
                            ->orWhere('volume', 'like', "%{$search}%")
                            ->orWhere('issue', 'like', "%{$search}%")
                            ->orWhere('pages', 'like', "%{$search}%")
                            ->orWhere('doi', 'like', "%{$search}%")
                            ->orWhereHas('item', function ($itemQuery) use ($search) {
                                $itemQuery
                                    ->where('title', 'like', "%{$search}%")
                                    ->orWhere('subtitle', 'like', "%{$search}%")
                                    ->orWhere('description', 'like', "%{$search}%")
                                    ->orWhere('call_number', 'like', "%{$search}%")
                                    ->orWhere('language', 'like', "%{$search}%")
                                    ->orWhere('keywords', 'like', "%{$search}%");
                            });
                    });
                }

                $allowedSorts = [
                    'id',
                    'isbn_issn',
                    'volume',
                    'issue',
                    'doi',
                    'created_at',
                    'updated_at',
                ];

                $sort = in_array($filters['sort'], $allowedSorts)
                    ? $filters['sort']
                    : 'id';

                $order = in_array($filters['order'], ['asc', 'desc'])
                    ? $filters['order']
                    : 'asc';

                return $query
                    ->orderBy($sort, $order)
                    ->paginate(
                        $filters['per_page'],
                        ['*'],
                        'page',
                        $filters['page']
                    );
            }
        );
    }

    public function create(array $data): Serial
    {
        $serial = DB::transaction(function () use (&$data){
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
                    'item_type_id',
                    'item_type_category_id',
                    'branch_id'
                ])
            );

            $serial = $item->serial()->create(
                arr::only($data, [
                    'isbn_issn',
                    'volume',
                    'issue',
                    'pages',
                    'doi',
                ])
            );

            IndexCatalogItemJob::dispatch($item->id)
                ->afterCommit();

            return $serial;
            
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
                    'item_type_id',
                    'item_type_category_id',
                    'branch_id'
                ])
            );

            $serial->update(
                arr::only($data, [
                    'isbn_issn',
                    'volume',
                    'issue',
                    'pages',
                    'doi',
                ])
            );
            // Queue indexing after the transaction commits.
            IndexCatalogItemJob::dispatch($serial->item_id)
                ->afterCommit();
                
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

            return true;
        });

        if($deleted){
            CacheService::invalidate(CacheService::SERIALS);
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