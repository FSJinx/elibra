<?php

namespace App\Services;

use App\Models\LoanMode;
use App\Models\PatronType;
use App\Models\PatronTypeLoanPolicy;
use Illuminate\Support\Facades\DB;

class LoanModeService
{
    public function create(array $data): LoanMode
    {
        return DB::transaction(function () use ($data) {
            $loan_mode = LoanMode::create($data);

            foreach (PatronType::pluck('id') as $id) {
                PatronTypeLoanPolicy::create([
                    'can_reserve' => fake()->boolean(),
                    'patron_type_id' => $id,
                    'loan_mode_id' => $loan_mode->id,
                ]);
            }

            return $loan_mode;
        });
    }
}
