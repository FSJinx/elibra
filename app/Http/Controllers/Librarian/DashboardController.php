<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Accession;
use App\Models\Branch;
use App\Models\Campus;
use App\Models\Circulation;
use App\Models\Item;
use App\Models\Librarian;
use App\Models\Library;
use App\Models\Patron;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Apply the appropriate data scope based on the authenticated user.
     */
    private function scopedItems(): Builder
    {
        $user = $this->user();

        $query = Item::query();

        if ($user?->isAdmin()) {
            // Admin can see all collections within their campus.
            $query->whereHas('library', function (Builder $query) use ($user) {
                $query->where('campus_id', $user->campus_id);
            });
        } elseif ($user?->isLibrarian()) {
            // Librarian can only see collections in their assigned branch.
            $query->where('library_id', $user->librarian?->library_id);
        }

        return $query;
    }

    private function scopedCirculations(): Builder
    {
        $user = $this->user();
        $query = Circulation::query();

        if ($user?->isAdmin()) {
            $query->whereHas('accession.item.library', function (Builder $query) use ($user) {
                $query->where('campus_id', $user->campus_id);
            });
        } elseif ($user?->isLibrarian()) {
            $query->whereHas('accession.item', function (Builder $query) use ($user) {
                $query->where('library_id', $user->librarian?->library_id);
            });
        }

        return $query;
    }

    private function scopedPatrons(): Builder
    {
        $user = $this->user();
        $query = Patron::query();

        if ($user?->isAdmin()) {
            $query->whereHas('user', function (Builder $query) use ($user) {
                $query->where('campus_id', $user->campus_id);
            });
        } elseif ($user?->isLibrarian()) {
            $libraryId = $user->librarian?->library_id;
            $patronIds = Circulation::query()
                ->whereHas('accession.item', fn (Builder $query) => $query->where('library_id', $libraryId))
                ->distinct()
                ->pluck('patron_id');

            $query->whereIn('patrons.id', $patronIds);
        }

        return $query;
    }

    public function summary()
    {
        $items = $this->scopedItems();
        $circulations = $this->scopedCirculations();
        $patrons = $this->scopedPatrons();
        $user = $this->user();

        $librarians = Librarian::query();
        $libraries = Library::query();

        if ($user?->isAdmin()) {
            $librarians->whereHas('library', fn (Builder $query) => $query->where('campus_id', $user->campus_id));
            $libraries->where('campus_id', $user->campus_id);
        } elseif ($user?->isLibrarian()) {
            $librarians->where('library_id', $user->librarian?->library_id);
            $libraries->where('id', $user->librarian?->library_id);
        }

        $accessions = Accession::query()->whereHas('item', function (Builder $query) use ($items) {
            $itemIds = (clone $items)->select('items.id');
            $query->whereIn('items.id', $itemIds);
        });

        $dailyActivity = [];
        for ($offset = 13; $offset >= 0; $offset--) {
            $date = now()->subDays($offset)->toDateString();
            $dailyActivity[] = [
                'date' => $date,
                'label' => now()->subDays($offset)->format('M j'),
                'borrowed' => (clone $circulations)->whereDate('borrowed_at', $date)->count(),
                'returned' => (clone $circulations)->whereDate('returned_at', $date)->count(),
            ];
        }

        $itemTypeBreakdown = (clone $items)
            ->join('item_types', 'items.item_type_id', '=', 'item_types.id')
            ->select('item_types.name', DB::raw('COUNT(items.id) as total'))
            ->groupBy('item_types.id', 'item_types.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($row) => ['name' => $row->name, 'total' => (int) $row->total])
            ->values();

        $patronTypeBreakdown = (clone $patrons)
            ->join('patron_types', 'patrons.patron_type_id', '=', 'patron_types.id')
            ->select('patron_types.name', DB::raw('COUNT(patrons.id) as total'))
            ->groupBy('patron_types.id', 'patron_types.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($row) => ['name' => $row->name, 'total' => (int) $row->total])
            ->values();

        $accessionStatusBreakdown = (clone $accessions)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'total' => (int) $row->total])
            ->values();

        $circulationStatusBreakdown = (clone $circulations)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'total' => (int) $row->total])
            ->values();

        $recentCirculations = (clone $circulations)
            ->with([
                'patron.user:id,last_name,first_name,middle_initial',
                'patron.patronType:id,name',
                'accession.item:id,title,call_number',
                'loanMode:id,name',
            ])
            ->latest('borrowed_at')
            ->limit(8)
            ->get()
            ->map(fn (Circulation $loan) => [
                'id' => $loan->id,
                'status' => $loan->status,
                'borrowed_at' => $loan->borrowed_at,
                'due_at' => $loan->due_at,
                'returned_at' => $loan->returned_at,
                'borrower' => $loan->patron?->user
                    ? trim(($loan->patron->user->last_name ?? '').', '.($loan->patron->user->first_name ?? ''))
                    : 'Unknown patron',
                'patron_type' => $loan->patron?->patronType?->name,
                'title' => $loan->accession?->item?->title,
                'call_number' => $loan->accession?->item?->call_number,
                'loan_mode' => $loan->loanMode?->name,
            ]);

        $recentItems = (clone $items)
            ->with(['itemType:id,name', 'library:id,name'])
            ->latest('created_at')
            ->limit(8)
            ->get()
            ->map(fn (Item $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'call_number' => $item->call_number,
                'publication_year' => $item->publication_year,
                'item_type' => $item->itemType?->name,
                'library' => $item->library?->name,
                'created_at' => $item->created_at,
            ]);

        $today = now()->toDateString();

        $data = [
            'metrics' => [
                'catalog_items' => (clone $items)->count(),
                'copies' => (clone $accessions)->count(),
                'available_copies' => (clone $accessions)->where('status', 'available')->count(),
                'borrowed_copies' => (clone $accessions)->where('status', 'borrowed')->count(),
                'active_loans' => (clone $circulations)->whereIn('status', ['borrowed', 'overdue'])->count(),
                'overdue_loans' => (clone $circulations)->whereIn('status', ['borrowed', 'overdue'])->where('due_at', '<', now())->count(),
                'returned_today' => (clone $circulations)->whereDate('returned_at', $today)->count(),
                'borrowed_today' => (clone $circulations)->whereDate('borrowed_at', $today)->count(),
                'total_circulations' => (clone $circulations)->count(),
                'patrons' => (clone $patrons)->count(),
                'librarians' => $librarians->count(),
                'campuses' => $user?->isSuperAdmin() ? Campus::count() : ($user?->isAdmin() ? 1 : (int) ($user->librarian?->library?->campus_id ? 1 : 0)),
                'fine_balance' => (float) (clone $circulations)->sum('fine_charged'),
            ],
            'circulation_statuses' => $circulationStatusBreakdown,
            'accession_statuses' => $accessionStatusBreakdown,
            'item_types' => $itemTypeBreakdown,
            'patron_types' => $patronTypeBreakdown,
            'daily_activity' => $dailyActivity,
            'recent_circulations' => $recentCirculations,
            'recent_items' => $recentItems,
        ];

        return $this->response(data: $data);
    }

    /**
     * Total number of collections.
     */
    public function totalCollections()
    {
        $total = $this->scopedItems()->count();

        return $this->response(data: $total);
    }

    /**
     * Total number of users.
     */
    public function totalPatrons()
    {
        $user = $this->user();

        $query = Patron::query();

        if ($user?->isAdmin()) {
            $query->whereHas('user', function (Builder $query) use ($user) {
                $query->where('campus_id', $user->campus_id);
            });
        } elseif ($user?->isLibrarian()) {
            $query->whereHas('user', function (Builder $query) use ($user) {
                $query->where('campus_id', $user->librarian?->library->campus_id);
            });
        }

        return $this->response(data: [
            'total_patrons' => $query->count(),
        ]);
    }

    public function totalLibrarians()
    {
        $user = $this->user();

        $query = User::query()
            ->whereHas('librarian');

        if ($user?->isAdmin()) {
            $query->whereHas('librarian.library', function (Builder $query) use ($user) {
                $query->where('campus_id', $user->campus_id);
            });
        } elseif ($user?->isLibrarian()) {
            $query->whereHas('librarian', function (Builder $query) use ($user) {
                $query->where('library_id', $user->librarian?->library_id);
            });
        }

        return $this->response(data: [
            'total_librarians' => $query->count(),
        ]);
    }

    public function totalCampuses()
    {
        return $this->response(data: [
            'total_campuses' => Campus::count(),
        ]);
    }
}
