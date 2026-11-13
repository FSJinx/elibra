<?php

namespace App\Console\Commands;

use App\Models\Circulation;
use App\Models\FinesTransaction;
use App\Models\PatronTypeLoanPolicy;
use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateOverdueCirculations extends Command
{
    protected $signature = 'circulations:update-overdue';

    protected $description = 'Mark overdue circulations and apply outstanding fines';

    public function handle(): int
    {
        $now = Carbon::now();
        $updated = 0;

        Circulation::query()
            ->whereIn('status', ['borrowed', 'overdue'])
            ->where('due_at', '<', $now)
            ->chunkById(100, function ($circulations) use ($now, &$updated): void {
                foreach ($circulations as $circulation) {
                    $wasUpdated = DB::transaction(function () use ($circulation, $now): bool {
                        $loan = Circulation::query()
                            ->lockForUpdate()
                            ->with('patron')
                            ->find($circulation->id);

                        if (! $loan || ! in_array($loan->status, ['borrowed', 'overdue'], true)) {
                            return false;
                        }

                        $policy = PatronTypeLoanPolicy::query()
                            ->where('patron_type_id', $loan->patron?->patron_type_id)
                            ->where('loan_mode_id', $loan->loan_mode_id)
                            ->first();

                        if (! $policy) {
                            return false;
                        }

                        $overdueSince = $loan->due_at->copy()->addDays($policy->grace_period);
                        if ($now->lessThanOrEqualTo($overdueSince)) {
                            return false;
                        }

                        $overdueDays = max(1, $overdueSince->diffInDays($now));
                        $expectedFine = round((float) $policy->fine_per_due * $overdueDays, 2);
                        $outstandingFine = round($expectedFine - (float) $loan->fine_charged, 2);

                        if ($outstandingFine > 0) {
                            FinesTransaction::create([
                                'amount' => $outstandingFine,
                                'transaction_type' => 'charged',
                                'remarks' => sprintf('Automatic overdue fine for %d day(s).', $overdueDays),
                                'patron_id' => $loan->patron_id,
                                'circulation_id' => $loan->id,
                            ]);
                        }

                        $loan->update([
                            'status' => 'overdue',
                            'fine_charged' => max((float) $loan->fine_charged, $expectedFine),
                        ]);

                        return true;
                    });

                    if ($wasUpdated) {
                        $updated++;
                    }
                }
            });

        if ($updated > 0) {
            CacheService::invalidate(CacheService::CIRCULATIONS);
            CacheService::invalidate(CacheService::FINES_TRANSACTIONS);
        }

        $this->info("Updated {$updated} overdue circulation(s).");

        return self::SUCCESS;
    }
}
