<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Item;

class DashboardController extends Controller
{
    public function totalCollections()
    {
        $user = $this->user();
        $query = Item::query();

        if ($user?->isAdmin()) {
            $query->whereHas('branch', function ($query) use ($user) {
                $query->where('campus_id', $user->campus_id);
            });
        } elseif ($user?->isLibrarian()) {
            $query->where('branch_id', $user->librarian?->branch_id);
        }

        return $this->response(data: [
            'total_collections' => $query->count(),
        ]);
    }
}
