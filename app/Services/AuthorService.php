<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AuthorService
{
    public function create(array $data): Author
    {
        $author = DB::transaction(function () use ($data) {
            return Author::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'suffix' => $data['suffix'] ?? null,
            ]);
        });

        CacheService::invalidate(CacheService::AUTHORS);

        return $author;
    }

    public function update(Author $author, array $data): Author
    {
        return DB::transaction(function () use ($author, $data) {
            $author->update([
                Arr::only($data, [
                    'first_name',
                    'middle_name',
                    'last_name',
                    'suffix',
                ]),
            ]);

            CacheService::invalidate(CacheService::AUTHORS);

            return $author->fresh();
        });
    }

    public function delete(Author $author): bool
    {
        $deleted = DB::transaction(function () use ($author) {
            return $author->delete();
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::AUTHORS);
        }

        return $deleted;
    }
}
