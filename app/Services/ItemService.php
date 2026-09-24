<?php

namespace App\Services;

use App\Models\Item;
use App\Models\User;

class ItemService
{
    public function index(array $filters, User $user)
    {
        $cacheFilters = array_merge($filters, [
            'campus_id' => $user->isAdmin() ? $user->campus_id : null,
            'branch_id' => $user->isLibrarian() ? $user->librarian?->branch_id : null,
        ]);

        return CacheService::remember(
            CacheService::ITEMS,
            $cacheFilters,
            now()->addMinutes(10),
            function () use ($filters, $user) {

                $query = Item::query()->with('coverMedia');
                $search = $filters['search'];
                $sort = $filters['sort'];
                $order = $filters['order'];

                if ($user->isAdmin()) {
                    $query->whereHas('branch', function ($query) use ($user) {
                        $query->where('campus_id', $user->campus_id);
                    });
                } elseif ($user->isLibrarian()) {
                    $query->where('branch_id', $user->librarian?->branch_id);
                }

                if ($search && $search != '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                        $q->orWhere('subtitle', 'like', "%{$search}%");
                        $q->orWhere('description', 'like', "%{$search}%");
                        $q->orWhere('keywords', 'like', "%{$search}%");
                    });
                }

                return $query
                    ->orderBy($sort, $order)
                    ->paginate(
                        $filters['per_page'],
                        ['*'],
                        'page',
                        $filters['page']
                    );
            }
        );
    }

    public function show(string $id)
    {
        $item = Item::findOrFail($id);

        return $item;
    }
}
