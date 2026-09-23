<?php

namespace App\Services;

use App\Models\PatronTypeLoanPolicy;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PatronTypeLoanPolicyService
{
    public function create(array $data): PatronTypeLoanPolicy
    {
        $policy = DB::transaction(function () use ($data) {
            $policy = PatronTypeLoanPolicy::create(
                Arr::only($data, [
                    'name',
                    'key',
                    'description',

                    'can_reserve',
                    'reservation_limit',
                    'loan_period_days',

                    'max_items',
                    'max_renewals',
                    'fine_per_due',
                    'grace_period',

                    'notes',
                    'patron_type_id',
                    'loan_mode_id'
                ])
            );

            return $policy;
        });
        CacheService::invalidate(CacheService::PATRON_TYPE_LOAN_POLICY);

        return $policy->load([
            'patronType',
            'loanMode',
        ]);
    }

    public function update( PatronTypeLoanPolicy $policy, array $data): PatronTypeLoanPolicy
    {
        DB::transaction(function () use ($policy, $data) {
            $policy->update(
                Arr::only($data, [
                    'name',
                    'key',
                    'description',
                    'can_reserve',
                    'reservation_limit',
                    'loan_period_days',
                    'max_items',
                    'max_renewals',
                    'fine_per_due',
                    'grace_period',
                    'notes',
                    'patron_type_id',
                    'loan_mode_id',
                ])
            );
        });

        CacheService::invalidate( CacheService::PATRON_TYPE_LOAN_POLICY);

        return $policy->fresh([
            'patronType',
            'loanMode',
        ]);
    }

    public function delete(PatronTypeLoanPolicy $policy): bool
    {
        $deleted = DB::transaction(function () use ($policy) {
            $policy->delete();

            return true;
        });

        if($deleted) {
            CacheService::invalidate(CacheService::PATRON_TYPE_LOAN_POLICY);
        }

        return $deleted;
    }   
}