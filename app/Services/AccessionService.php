<?php

namespace App\Services;

use App\Models\Accession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AccessionService
{
    public function create(array $data): Accession
    {
        $accession = DB::transaction(function () use ($data) {
            return Accession::create(
                Arr::only($data, [
                    'accession_number',
                    'status',
                    'remarks',

                    'item_id',
                    'section_id',
                    'acquisition_line_id',
                ])
            );

        });

        CacheService::invalidate(CacheService::ACCESSION);

        return $accession;
    }

    public function update(Accession $accession, array $data): Accession
    {
        $accession = DB::transaction(function () use ($accession, $data) {
            $accession->update(
                Arr::only($data, [
                    'accession_number',
                    'status',
                    'remarks',

                    'item_id',
                    'section_id',
                    'acquisition_line_id',
                ])
            );

            return $accession->fresh();
        });

        CacheService::invalidate(CacheService::ACCESSION);

        return $accession;
    }

    public function delete(Accession $accession): void
    {
        DB::transaction(function () use ($accession) {
            $accession->delete();
        });

        CacheService::invalidate(CacheService::ACCESSION);
    }
}