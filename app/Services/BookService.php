<?php

namespace App\Services;

use App\Jobs\IndexCatalogItemJob;
use App\Models\Book;
use App\Models\Item;
use App\Models\Language;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookService
{
    public function index(array $filters)
    {
        return CacheService::remember(
            CacheService::ACADEMICS,
            $filters,
            now()->addMinutes(10),
            function () use ($filters) {

                $query = Book::query()
                    ->with([
                        'item.language',
                        'department',
                    ]);

                if ($filters['search'] !== '') {
                    $search = $filters['search'];

                    $query->where(function ($q) use ($search) {
                        $q->where('doi', 'like', "%{$search}%")
                            ->orWhere('subjects', 'like', "%{$search}%")
                            ->orWhereHas('item', function ($itemQuery) use ($search) {
                                $itemQuery
                                    ->where('title', 'like', "%{$search}%")
                                    ->orWhere('subtitle', 'like', "%{$search}%")
                                    ->orWhere('description', 'like', "%{$search}%")
                                    ->orWhere('call_number', 'like', "%{$search}%")
                                    ->orWhereHas('language', function ($languageQuery) use ($search) {
                                        $languageQuery
                                            ->where('name', 'like', "%{$search}%")
                                            ->orWhere('code', 'like', "%{$search}%");
                                    })
                                    ->orWhere('keywords', 'like', "%{$search}%");
                            });
                    });
                }

                $allowedSorts = [
                    'id',
                    'doi',
                    'department_id',
                    'created_at',
                    'updated_at',
                ];

                $sort = $filters['sort'];

                if (! in_array($sort, $allowedSorts)) {
                    $sort = 'id';
                }

                $order = in_array($filters['order'], ['asc', 'desc'])
                    ? $filters['order']
                    : 'asc';

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

    public function create(array $data): Book
    {
        $book = DB::transaction(function () use (&$data) {

            $this->saveElectronicFile($data);
            $data = $this->normalizeItemData($data);

            $item = Item::create(
                Arr::only($data, [
                    'title',
                    'subtitle',
                    'description',
                    'call_number',
                    'publication_year',
                    'electronic_file',

                    'item_type_id',
                    'item_type_category_id',
                    'language_id',
                    'branch_id',

                    'keywords',
                ])
            );

            $book = $item->book()->create(
                Arr::only($data, [
                    'edition',
                    'isbn_issn',
                    'copyright_year',
                ])
            );

            $item->authors()->sync($data['author_ids'] ?? []);

            IndexCatalogItemJob::dispatch($item->id)
                ->afterCommit();

            return $book;
        });

        CacheService::invalidate(CacheService::BOOKS);

        return $book->load('item');
    }

    public function update(Book $book, array $data): Book
    {
        $oldFile = $book->item->electronic_file;

        $book = DB::transaction(function () use (&$book, &$data) {

            if (! $this->saveElectronicFile($data)) {
                unset($data['electronic_file']);
            }

            $data = $this->normalizeItemData($data);

            $book->item->update(
                Arr::only($data, [
                    'title',
                    'subtitle',
                    'description',
                    'call_number',
                    'language',
                    'publication_year',
                    'keywords',
                    'electronic_file',
                    'item_type_id',
                    'item_type_category_id',
                    'language_id',
                    'branch_id',
                ])
            );

            $book->update(
                Arr::only($data, [
                    'subjects',
                    'doi',
                    'department_id',
                ])
            );

            // Update authors
            $book->item->authors()->sync($data['author_ids'] ?? []);

            // Queue indexing after the transaction commits.
            IndexCatalogItemJob::dispatch($book->item_id)
                ->afterCommit();

            // Refresh the book model to get the latest data from the database
            return $book->fresh(['item']);
        });

        // Delete the old file only after the database update succeeds
        if (
            isset($data['electronic_file']) &&
            $oldFile &&
            $oldFile !== $data['electronic_file']
        ) {
            Storage::disk('public')->delete($oldFile);
        }

        CacheService::invalidate(CacheService::ACADEMICS);

        return $book;
    }

    public function delete(Book $book): bool
    {
        $deleted = DB::transaction(function () use ($book) {
            $book->item->delete();
            $book->delete();

            return true;
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::ACADEMICS);
        }

        return $deleted;
    }

    private function saveElectronicFile(array &$data): bool
    {
        if (
            isset($data['electronic_file']) &&
            $data['electronic_file'] instanceof UploadedFile
        ) {
            $data['electronic_file'] = $data['electronic_file']
                ->store('item/academics', 'public');

            return true;
        }

        return false;
    }

    private function normalizeItemData(array $data): array
    {
        if (array_key_exists('language', $data) && ! array_key_exists('language_id', $data)) {
            $language = Language::query()
                ->where('name', $data['language'])
                ->orWhere('code', $data['language'])
                ->firstOrFail();

            $data['language_id'] = $language->id;
        }

        unset($data['language']);

        if (array_key_exists('keywords', $data)) {
            $keywords = is_array($data['keywords'])
                ? $data['keywords']
                : preg_split('/\s*,\s*/', trim((string) $data['keywords']), -1, PREG_SPLIT_NO_EMPTY);

            $data['keywords'] = array_values(array_unique(array_map(
                fn (string $keyword): string => Str::lower(trim($keyword)),
                $keywords
            )));
        }

        return $data;
    }
}
