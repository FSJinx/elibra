<?php

namespace App\Services;

use App\Models\Vendor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class VendorService
{
    public function create(array $data): Vendor
    {
        $vendor = DB::transaction(function () use ($data) {
            return Vendor::create([
                'name' => $data['name'],
                'address' => $data['address'] ?? null,
            ]);
        });

        CacheService::invalidate(CacheService::VENDORS);

        return $vendor;
    }

    public function update(Vendor $vendor, array $data): Vendor
    {
        return DB::transaction(function () use ($vendor, $data) 
        {
            $vendor->update([
                Arr::only($data, [
                    'name',
                    'address',
                ]),
            ]);

            CacheService::invalidate(CacheService::VENDORS);

            return $vendor->fresh();

        });
    }

    public function delete(Vendor $vendor): bool
    {
        $deleted = DB::transaction(function () use ($vendor) {
            return $vendor->delete();
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::VENDORS);
        }

        return $deleted;
    }
}