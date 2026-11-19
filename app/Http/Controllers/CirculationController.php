<?php

namespace App\Http\Controllers;

use App\Enums\ReportType;
use App\Http\Requests\StoreCirculationRequest;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateCirculationRequest;
use App\Models\Accession;
use App\Models\Circulation;
use App\Models\User;
use App\Services\CirculationService;
use App\Services\QueryService;
use App\Services\ReportQueryService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CirculationController extends Controller
{
    public function __construct(
        protected CirculationService $circulationService,
        protected ReportService $report,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $query = Circulation::query()
            ->when($filters['library_id'] ?? null, fn ($q, $libraryId) => $q->whereHas('accession.item', fn ($qq) => $qq->where('library_id', $libraryId)))
            ->when($filters['patron_id'] ?? null, fn ($q, $patronId) => $q->where('patron_id', $patronId))
            ->when(($filters['status'] ?? null) === 'active', fn ($q) => $q->whereIn('status', ['borrowed', 'overdue']))
            ->when(($filters['status'] ?? null) && $filters['status'] !== 'active', fn ($q, $status) => $q->where('status', $status))
            ->when($filters['query'] ?? null, function ($q, $search) {
                $q->where(function ($searchQuery) use ($search) {
                    $searchQuery->whereHas('accession', function ($accession) use ($search) {
                        $accession->where('accession_number', 'LIKE', "%{$search}%")
                            ->orWhereHas('item', fn ($item) => $item->where('title', 'LIKE', "%{$search}%"));
                    })->orWhereHas('patron.user', function ($user) use ($search) {
                        $user->whereRaw("CONCAT_WS(' ', first_name, middle_initial, last_name) LIKE ?", ["%{$search}%"])
                            ->orWhereRaw("CONCAT_WS(' ', last_name, first_name) LIKE ?", ["%{$search}%"])
                            ->orWhere('username', 'LIKE', "%{$search}%");
                    });
                });
            })
            ->with(['processor.user',    'patron.user',    'patron.patronType',    'accession.item',    'loanMode',    'returnReceivedBy'])
            ->latest('borrowed_at');

        if (($filters['group_by'] ?? null) === 'patron') {
            $records = $query->get();
            $groups = $records->groupBy(fn (Circulation $loan) => $loan->patron_id)
                ->map(function ($loans) {
                    $latest = $loans->sortByDesc('borrowed_at')->first();
                    $patron = $latest->patron;

                    return [
                        'patron_id' => $patron?->id,
                        'patron' => $patron,
                        'quantity' => $loans->count(),
                        'latest_borrowed_at' => $latest->borrowed_at,
                        'latest_due_at' => $latest->due_at,
                        'overdue_quantity' => $loans->where('status', 'overdue')->count(),
                        'fine_charged' => (float) $loans->sum('fine_charged'),
                        'loans' => $loans->values(),
                    ];
                })
                ->sortByDesc('latest_borrowed_at')
                ->values();

            $page = max(1, request()->integer('page', 1));
            $perPage = request()->integer('per_page', 25);
            $items = $groups->forPage($page, $perPage)->values();
            $circulations = new LengthAwarePaginator(
                $items,
                $groups->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            $circulations = $query->paginate(request()->integer('per_page', 25));
        }

        return $this->response(
            'success',
            'Circulations retrieved successfully.',
            $circulations->toArray(),
            200
        );

    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'patron' => ['required', 'string', 'max:255'],
            'accession' => ['required', 'string', 'max:255'],
        ]);

        $params = [
            'patron' => $validated['patron'],
            'accession' => trim($validated['accession']),
        ];

        $patron = User::query()
            ->where('role', 'patron')
            ->where(function ($q) use ($params) {
                $q->where('username', $params['patron'])
                    ->orWhereHas('patron', function ($p) use ($params) {
                        $p->where('ebc_number', $params['patron']);
                    });
            })
            ->select(['id', 'last_name', 'first_name', 'username', 'middle_initial', 'email'])
            ->with(['patron.patronType:id,name', 'patron.program:id,name,department_id', 'patron.program.department:id,name'])
            ->first();

        if (! $patron) {
            return $this->response('error', 'Patron not found.', null, 404);
        }

        $accession = Accession::query()
            ->select(['id', 'accession_number', 'status', 'item_id'])
            ->with(['item:id,title,description,call_number'])
            ->where('accession_number', $params['accession'])
            ->first();

        if (! $accession || ! $accession->item) {
            return $this->response('error', 'Accession or catalog item not found.', null, 404);
        }

        return $this->response(
            'success',
            'Patron and item found.',
            [
                'patron' => $patron,
                'accession' => $accession,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCirculationRequest $request)
    {
        $validated = $request->validated();
        $circulations = DB::transaction(function () use ($validated) {
            $created = [];

            foreach ($validated['accessions'] as $accession) {
                $created[] = $this->circulationService->checkout([
                    'patron_id' => $validated['patron']['id'],
                    'accession_id' => $accession['id'],
                    'loan_mode_id' => $validated['loan_mode_id'],
                ], $this->user());
            }

            return $created;
        });

        return $this->response(
            'success',
            'Circulations created successfully.',
            array_map(fn (Circulation $circulation) => $circulation->toArray(), $circulations),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Circulation $circulation)
    {
        $circulation->load([
            'processor.user',
            'patron.user',
            'accession',
            'loanMode',
            'returnReceivedBy',
        ]);

        return $this->response(
            'success',
            'Circulation retrieved successfully.',
            $circulation->toArray(),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCirculationRequest $request, Circulation $circulation)
    {
        $updated = $this->circulationService->update($circulation, $request->validated());

        return $this->response(
            'success',
            'Circulation updated successfully.',
            $updated->toArray(),
            200
        );
    }

    public function return(Circulation $circulation, Request $request)
    {
        $returned = $this->circulationService->returnLoan($circulation, $request->user());

        return $this->response(
            'success',
            'Loan returned successfully. The item is available again.',
            $returned->toArray(),
            200
        );
    }

    public function renew(Circulation $circulation)
    {
        $renewed = $this->circulationService->renew($circulation);

        return $this->response(
            'success',
            'Loan renewed successfully. The due date has been extended according to the loan policy.',
            $renewed->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Circulation $circulation)
    {
        $deleted = $this->circulationService->delete($circulation);

        if (! $deleted) {
            return $this->response(
                'error',
                'The selected circulation record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Circulation record deleted successfully.',
            null,
            200
        );
    }

    public function generateReport(StoreReportRequest $request)
    {
        $filters = ReportQueryService::filters(ReportType::Circulation, $request);
        $libraryId = $this->user()->librarian?->library_id;

        $data = Circulation::query()
            ->when($libraryId, fn ($q) => $q->whereHas('accession.item', fn ($item) => $item->where('library_id', $libraryId)))
            ->when($filters['created_from'], fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($filters['created_to'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d))
            ->with(['accession.item', 'patron.user', 'patron.patronType', 'processor.user'])
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'accession_number' => $c->accession->accession_number,
                'title' => $c->accession->item->title,
                'borrower' => "{$c->patron->user->first_name} {$c->patron->user->last_name}",
                'patron_type' => $c->patron->patronType->name,
                'borrowed_at' => $c->borrowed_at,
                'due_at' => $c->due_at,
                'returned_at' => $c->returned_at,
                'status' => $c->status,
            ])->all();

        if (empty($data)) {
            return $this->response('error', 'No data in circulation yet!');
        }

        $report = $this->report->create(ReportType::Circulation, $filters, $data, $this->user());

        return $this->response('success', 'Report generated successfully!', $report);
    }
}
