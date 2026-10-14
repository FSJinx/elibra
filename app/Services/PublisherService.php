<?php

namespace App\Services;

use App\Models\Publisher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PublisherService
{
    public function create(array $data): Publisher
    {
        $publisher = DB::transaction(function () use ($data) {
            return Publisher::create([
                'name' => $data['name'],
                'address' => $data['address'] ?? null,
            ]);
        });

        CacheService::invalidate(CacheService::PUBLISHERS);

        return $publisher;
    }

    public function update(Publisher $publisher, array $data): Publisher
    {
        return DB::transaction(function () use ($publisher, $data) 
        {
            $publisher->update([
                Arr::only($data, [
                    'name',
                    'address',
                ]),
            ]);

            CacheService::invalidate(CacheService::PUBLISHERS);

            return $publisher->fresh();

        });
    }

    public function delete(Publisher $publisher): bool
    {
        $deleted = DB::transaction(function () use ($publisher) {
            return $publisher->delete();
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::PUBLISHERS);
        }

        return $deleted;
    }
}