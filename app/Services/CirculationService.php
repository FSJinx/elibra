<?php

namespace App\Services;

use App\Models\Circulation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CirculationService
{
    public function create(array $data): Circulation
    {
        $circulation = DB::transaction(function () use ($data) {
            $circulation = Circulation::create(
                Arr::only($data, [
                    'borrowed_at',
                    'due_at',
                    'returned_at',
                    'status',
                    'renewal_count',
                    'fine_charged',
                    'notes',
                    'processed_by',
                    'accession_id',
                    'patron_id',
                    'loan_mode_id',
                    'return_received_by',
                ])
            );

            return $circulation;
        });

        CacheService::invalidate(CacheService::CIRCULATIONS);

        return $circulation->load([
            'processedBy',
            'patron',
            'accession',
            'loanMode',
            'returnReceivedBy',
        ]);
    }

    public function update(Circulation $circulation, array $data): Circulation
    {
        $updated = DB::transaction(function () use ($circulation, $data) {
            $circulation->update(
                Arr::only($data, [
                    'borrowed_at',
                    'due_at',
                    'returned_at',
                    'status',
                    'renewal_count',
                    'fine_charged',
                    'notes',
                    'processed_by',
                    'accession_id',
                    'patron_id',
                    'loan_mode_id',
                    'return_received_by',
                ])
            );

            return $circulation;
        });

        CacheService::invalidate(CacheService::CIRCULATIONS);

        return $updated->fresh([
            'processedBy',
            'patron',
            'accession',
            'loanMode',
            'returnReceivedBy',
        ]);
    }

    public function delete(Circulation $circulation): bool
    {
        $deleted = DB::transaction(function () use ($circulation) {
            $circulation->delete();

            return true;
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::CIRCULATIONS);
        }

        return $deleted;
    }
}
