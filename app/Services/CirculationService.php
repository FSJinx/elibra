<?php

namespace App\Services;

use App\Models\Circulation;
use App\Models\Accession;
use App\Models\Patron;
use App\Models\PatronTypeLoanPolicy;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CirculationService
{
    public function checkout(array $data, User $processedBy): Circulation
    {
        $circulation = DB::transaction(function () use ($data, $processedBy) {
            $accession = Accession::query()
                ->lockForUpdate()
                ->findOrFail($data['accession_id']);

            if ($accession->status !== 'available') {
                throw ValidationException::withMessages([
                    'accession_id' => ['This item is not available for loan.'],
                ]);
            }

            $patron = Patron::query()
                ->with('patronType')
                ->lockForUpdate()
                ->findOrFail($data['patron_id']);

            $policy = PatronTypeLoanPolicy::query()
                ->where('patron_type_id', $patron->patron_type_id)
                ->where('loan_mode_id', $data['loan_mode_id'])
                ->first();

            if (! $policy) {
                throw ValidationException::withMessages([
                    'loan_mode_id' => ['There is no loan policy for this patron type and loan mode.'],
                ]);
            }

            $borrowedAt = Carbon::now();
            $circulation = Circulation::create([
                'borrowed_at' => $borrowedAt,
                'due_at' => $borrowedAt->copy()->addDays($policy->loan_period_days),
                'status' => 'borrowed',
                'renewal_count' => 0,
                'fine_charged' => 0,
                'processed_by' => $processedBy->librarian?->id,
                'accession_id' => $accession->id,
                'patron_id' => $patron->id,
                'loan_mode_id' => $data['loan_mode_id'],
            ]);

            $accession->update(['status' => 'on_load']);

            return $circulation;
        });

        CacheService::invalidate(CacheService::CIRCULATIONS);

        return $circulation->load([
            'processedBy.user',
            'patron.user',
            'patron.patronType',
            'accession.item',
            'loanMode',
            'returnReceivedBy',
        ]);
    }

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
            $circulation->update(Arr::only($data, ['status']));

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

    public function returnLoan(Circulation $circulation, User $receivedBy): Circulation
    {
        $returned = DB::transaction(function () use ($circulation, $receivedBy) {
            $loan = Circulation::query()
                ->lockForUpdate()
                ->findOrFail($circulation->id);

            if (! in_array($loan->status, ['borrowed', 'overdue'], true)) {
                throw ValidationException::withMessages([
                    'status' => ['Only borrowed or overdue loans can be returned.'],
                ]);
            }

            $accession = Accession::query()
                ->lockForUpdate()
                ->findOrFail($loan->accession_id);

            $loan->update([
                'status' => 'returned',
                'returned_at' => Carbon::now(),
                'return_received_by' => $receivedBy->librarian?->id,
            ]);

            if ($accession->status === 'on_load') {
                $accession->update(['status' => 'available']);
            }

            return $loan;
        });

        CacheService::invalidate(CacheService::CIRCULATIONS);

        return $returned->fresh([
            'processedBy.user',
            'patron.user',
            'patron.patronType',
            'accession.item',
            'loanMode',
            'returnReceivedBy.user',
        ]);
    }

    public function renew(Circulation $circulation): Circulation
    {
        $renewed = DB::transaction(function () use ($circulation) {
            $loan = Circulation::query()
                ->with('patron')
                ->lockForUpdate()
                ->findOrFail($circulation->id);

            if (! in_array($loan->status, ['borrowed', 'overdue'], true)) {
                throw ValidationException::withMessages([
                    'status' => ['Only borrowed or overdue loans can be renewed.'],
                ]);
            }

            $loan->loadMissing('loanMode');
            if (! $loan->loanMode?->renewable) {
                throw ValidationException::withMessages([
                    'loan_mode_id' => ['This loan mode does not allow renewals.'],
                ]);
            }

            if (! $loan->patron) {
                throw ValidationException::withMessages([
                    'patron_id' => ['The patron associated with this loan could not be found.'],
                ]);
            }

            $policy = PatronTypeLoanPolicy::query()
                ->where('patron_type_id', $loan->patron->patron_type_id)
                ->where('loan_mode_id', $loan->loan_mode_id)
                ->first();

            if (! $policy) {
                throw ValidationException::withMessages([
                    'loan_mode_id' => ['There is no loan policy for this patron type and loan mode.'],
                ]);
            }

            if ($loan->renewal_count >= $policy->max_renewals) {
                throw ValidationException::withMessages([
                    'renewal_count' => ['This loan has reached the maximum number of renewals.'],
                ]);
            }

            $now = Carbon::now();
            $extensionBase = $loan->due_at->greaterThan($now) ? $loan->due_at->copy() : $now;

            $loan->update([
                'due_at' => $extensionBase->addDays($policy->loan_period_days),
                'renewal_count' => $loan->renewal_count + 1,
                'status' => 'borrowed',
            ]);

            return $loan;
        });

        CacheService::invalidate(CacheService::CIRCULATIONS);

        return $renewed->fresh([
            'processedBy.user',
            'patron.user',
            'patron.patronType',
            'accession.item',
            'loanMode',
            'returnReceivedBy.user',
        ]);
    }

    public function delete(Circulation $circulation): bool
    {
        $deleted = DB::transaction(function () use ($circulation) {
            $accession = Accession::query()
                ->lockForUpdate()
                ->find($circulation->accession_id);

            $circulation->delete();

            if (
                $accession
                && $accession->status === 'on_load'
            ) {
                $accession->update(['status' => 'available']);
            }

            return true;
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::CIRCULATIONS);
        }

        return $deleted;
    }
}
