<?php

namespace App\Services;

use App\Models\AcquisitionLines;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AcquisitionLinesService
{
    public function create(array $data): AcquisitionLines
    {
        $acquisitionLine = DB::transaction(function () use ($data) {
            return AcquisitionLines::create(
                Arr::only($data, [
                    'quantity',
                    'unit_price',
                    'discount',
                    'net_price',
                    'item_id',
                    'acquisition_id',
                ])
            );
        });

        CacheService::invalidate(CacheService::ACQUISITION_LINES);

        return $acquisitionLine->fresh([
            'item',
            'acquisition',
        ]);
    }

    public function update(AcquisitionLines $acquisitionLine, array $data): AcquisitionLines
    {
        $acquisitionLine = DB::transaction(function () use ($acquisitionLine, $data) {
            $acquisitionLine->update(
                Arr::only($data, [
                    'quantity',
                    'unit_price',
                    'discount',
                    'net_price',
                    'item_id',
                    'acquisition_id',
                ])
            );

            return $acquisitionLine->fresh([
                'item',
                'acquisition',
            ]);
        });

        CacheService::invalidate(CacheService::ACQUISITION_LINES);

        return $acquisitionLine;
    }

    public function delete(AcquisitionLines $acquisitionLine): bool
    {
        $deleted = DB::transaction(function () use ($acquisitionLine) {
            
            $acquisitionLine->delete();

            return true;
        });
        if ($deleted) {
            CacheService::invalidate(CacheService::ACQUISITION_LINES);
        }

        return $deleted;
    }
}