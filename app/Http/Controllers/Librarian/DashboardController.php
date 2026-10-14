<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Campus;
use App\Models\Item;
use App\Models\Library;
use App\Models\Patron;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

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
                $query->where('branch_id', $user->librarian?->branch_id);
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
                $query->where('branch_id', $user->librarian?->branch_id);
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
