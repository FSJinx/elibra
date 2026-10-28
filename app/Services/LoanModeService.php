<?php

namespace App\Services;

use App\Models\LoanMode;
use App\Models\PatronType;
use App\Models\PatronTypeLoanPolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoanModeService
{
    public function create(array $data): LoanMode
    {
        $mode = DB::transaction(function () use ($data) {
            $loan_mode = LoanMode::create($data);

            foreach (PatronType::pluck('id') as $id) {
                PatronTypeLoanPolicy::create([
                    'patron_type_id' => $id,
                    'loan_mode_id' => $loan_mode->id,
                ]);
            }

            return $loan_mode;
        });

        CacheService::invalidate(CacheService::LOAN_MODES);
        Log::info('invalidated');

        return $mode;
    }
}
