<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHoldingRequest;
use App\Http\Requests\UpdateHoldingRequest;
use App\Models\Accession;
use App\Models\Circulation;
use App\Models\Holding;
use App\Models\PatronTypeLoanPolicy;
use App\Models\User;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HoldingController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->user();

        $filters = QueryService::filters($request);
        $filters = array_merge($filters, [
            'patron_id' => $request->input('patron_id', null),
        ]);

        $data = Holding::query()
            ->when($user->isSuperAdmin(), function ($q) {})
            ->when($user->isAdmin() || $user->isLibrarian(), function ($q) {
                return $q
                    ->with(['accession.item', 'processor.user', 'reserver.user']);
            })
            ->when($user->isPatron(), function ($q) use ($user) {
                return $q
                    ->where('reserver_id', $user->patron->id)
                    ->with(['accession.item', 'processor']);
            })
            ->paginate(
                $filters['per_page'],
                ['*'],
                'page',
                $filters['page']
            );

        return $this->response(data: $data);
    }

    public function store(StoreHoldingRequest $request)
    {
        $validated = $request->validated();

        if (Holding::where('accession_id', $validated['accession_id'])) {

        }
        $data = DB::transaction(function () use ($validated) {
            $hold = Holding::create($validated);
            $hold->accession->update(['status' => 'reserved']);

            return $hold;
        });

        return $this->response('success', 'Reservation completed successfully!', data: $data);
    }

    public function complete(UpdateHoldingRequest $request, Holding $holding)
    {
        $this->ensureHoldingVisible($request->user(), $holding);
        $loan = DB::transaction(function () use ($request, $holding) {
            $reservation = Holding::query()->lockForUpdate()->findOrFail($holding->id);
            if ($reservation->picked_up_at) {
                throw ValidationException::withMessages([
                    'holding' => ['This reservation has already been completed.'],
                ]);
            }

            $accession = Accession::query()->lockForUpdate()->findOrFail($reservation->accession_id);

            if ($accession->status !== 'reserved') {
                throw ValidationException::withMessages([
                    'accession_id' => ['The reserved item is no longer available for pickup.'],
                ]);
            }

            $patron = $reservation->reserver()->with('patronType')->first();
            if (! $patron) {
                throw ValidationException::withMessages([
                    'reserver_id' => ['The patron associated with this reservation could not be found.'],
                ]);
            }

            $policy = PatronTypeLoanPolicy::query()
                ->where('patron_type_id', $patron->patron_type_id)
                ->where('loan_mode_id', $request->validated('loan_mode_id'))
                ->first();

            if (! $policy) {
                throw ValidationException::withMessages([
                    'loan_mode_id' => ['There is no loan policy for this patron type and loan mode.'],
                ]);
            }

            $borrowedAt = Carbon::now();
            $loan = Circulation::create([
                'borrowed_at' => $borrowedAt,
                'due_at' => $borrowedAt->copy()->addDays($policy->loan_period_days),
                'status' => 'borrowed',
                'renewal_count' => 0,
                'fine_charged' => 0,
                'processed_by' => $request->user()->librarian?->id,
                'accession_id' => $accession->id,
                'patron_id' => $patron->id,
                'loan_mode_id' => $request->validated('loan_mode_id'),
            ]);

            $reservation->update([
                'status'=> 'completed',
                'pickup_date' => $borrowedAt,
                'processor_id' => $request->user()->librarian?->id,
            ]);
            $accession->update(['status' => 'borrowed']);

            return $loan;
        });

        return $this->response('success', 'Reservation completed and loan created successfully.', $loan->load(['patron.user', 'accession.item', 'loanMode']), 200);
    }

    public function destroy(Request $request, Holding $holding)
    {
        $this->ensureHoldingVisible($request->user(), $holding);

        DB::transaction(function () use ($holding) {
            $reservation = Holding::query()->lockForUpdate()->findOrFail($holding->id);
            $accession = Accession::query()->lockForUpdate()->find($reservation->accession_id);

            $reservation->delete();

            if ($accession?->status === 'reserved') {
                $accession->update(['status' => 'available']);
            }
        });

        return $this->response('success', 'Reservation deleted successfully.', null);
    }

    private function visibleHoldings(User $user)
    {
        return Holding::query()
            ->when($user->isLibrarian(), fn ($query) => $query->whereHas('accession.item', fn ($item) => $item->where('library_id', $user->librarian?->library_id)))
            ->when($user->isAdmin(), fn ($query) => $query->whereHas('accession.item.library', fn ($library) => $library->where('campus_id', $user->campus_id)));
    }

    private function ensureHoldingVisible(User $user, Holding $holding): void
    {
        abort_unless($this->visibleHoldings($user)->whereKey($holding->id)->exists(), 404);
    }
}
