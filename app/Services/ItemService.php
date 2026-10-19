<?php

namespace App\Services;

use App\Jobs\IndexCatalogItemJob;
use App\Models\Item;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemService
{
    public function __construct(private MediaService $mediaService) {}

    public function create(array $data): Item
    {
        return DB::transaction(function () use (&$data): Item {
            $this->saveElectronicFile($data);

            $item = Item::create(Arr::only($data, [
                'title',
                'subtitle',
                'description',
                'call_number',
                'electronic_file',
                'keywords',
                'edition',
                'isbn_issn',
                'doi',
                'volume',
                'issue',
                'pages',
                'department_id',
                'item_type_id',
                'item_type_category_id',
                'library_id',
                'language_id',
            ]));

            if (isset($data['authors'])) {
                $item->syncAuthors($data['authors']);
            }

            $this->saveCoverImage($item, $data);

            IndexCatalogItemJob::dispatch($item->id);

            CacheService::invalidate(CacheService::ITEMS);

            return $item->fresh(['coverMedia']);
        });
    }

    public function update(Item $item, array $data): Item
    {
        $oldElectronicFile = $item->electronic_file;
        $replacingElectronicFile = ($data['electronic_file'] ?? null) instanceof UploadedFile;

        $item = DB::transaction(function () use ($item, &$data): Item {
            if (($data['electronic_file'] ?? null) instanceof UploadedFile) {
                $data['electronic_file'] = $data['electronic_file']
                    ->store('item/files', 'public');
            } else {
                unset($data['electronic_file']);
            }

            $item->update(Arr::only($data, [
                'title',
                'subtitle',
                'description',
                'call_number',
                'electronic_file',
                'keywords',
                'edition',
                'isbn_issn',
                'doi',
                'volume',
                'issue',
                'pages',
                'department_id',
                'item_type_id',
                'item_type_category_id',
                'language_id',
            ]));

            if (array_key_exists('authors', $data) || array_key_exists('authors_present', $data)) {
                $item->syncAuthors($data['authors'] ?? []);
            }

            $this->saveCoverImage($item, $data);

            IndexCatalogItemJob::dispatch($item->id)->afterCommit();

            return $item->fresh(['coverMedia', 'authors']);
        });

        if ($replacingElectronicFile && $oldElectronicFile) {
            Storage::disk('public')->delete($oldElectronicFile);
        }

        CacheService::invalidate(CacheService::ITEMS);

        return $item;
    }

    public function index(array $filters, User $user)
    {
        $cacheFilters = array_merge($filters, [
            'campus_id' => $user->isAdmin() ? $user->campus_id : null,
            'library_id' => $user->isLibrarian() ? $user->librarian?->library_id : null,
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
                    $query->whereHas('library', function ($query) use ($user) {
                        $query->where('campus_id', $user->campus_id);
                    });
                } elseif ($user->isLibrarian()) {
                    $query->where('library_id', $user->librarian?->library_id);
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
        $item = Item::query()
            ->with(['authors', 'coverMedia'])
            ->findOrFail($id);

        return $item;
    }

    private function saveCoverImage(Item $item, array $data): void
    {
        if (! ($data['cover_image'] ?? null) instanceof UploadedFile) {
            return;
        }

        if ($item->coverMedia) {
            $this->mediaService->replaceFile($item->coverMedia, $data['cover_image']);

            return;
        }

        $media = $this->mediaService->store($data['cover_image'], Media::ITEM_COVER);
        $item->update(['cover_media_id' => $media->id]);
    }

    private function saveElectronicFile(array &$data): void
    {
        if (
            isset($data['electronic_file']) &&
            $data['electronic_file'] instanceof UploadedFile
        ) {
            $data['electronic_file'] = $data['electronic_file']
                ->store('item/files', 'public');
        }
    }
}
