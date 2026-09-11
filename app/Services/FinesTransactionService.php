<?php

namespace App\Services;

use App\Models\FinesTransaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FinesTransactionService
{
    public function create(array $data): FinesTransaction
    {
        $transaction = DB::transaction(function () use ($data) {
            $transaction = FinesTransaction::create(
                Arr::only($data, [
                    'amount',
                    'transaction_type',
                    'remarks',
                    'patron_id',
                    'circulation_id',
                    'processed_by',
                ])
            );

            return $transaction;
        });

        CacheService::invalidate(CacheService::FINES_TRANSACTIONS);

        return $transaction->load([
            'patron',
            'circulation',
            'processedBy',
        ]);
    }

    public function update(FinesTransaction $finesTransaction, array $data): FinesTransaction
    {
        $updated = DB::transaction(function () use ($finesTransaction, $data) {
            $finesTransaction->update(
                Arr::only($data, [
                    'amount',
                    'transaction_type',
                    'remarks',
                    'patron_id',
                    'circulation_id',
                    'processed_by',
                ])
            );

            return $finesTransaction;
        });

        CacheService::invalidate(CacheService::FINES_TRANSACTIONS);

        return $updated->fresh([
            'patron',
            'circulation',
            'processedBy',
        ]);
    }

    public function delete(FinesTransaction $finesTransaction): bool
    {
        $deleted = DB::transaction(function () use ($finesTransaction) {
            return $finesTransaction->delete();
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::FINES_TRANSACTIONS);
        }

        return $deleted;
    }
}
