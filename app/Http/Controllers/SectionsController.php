<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionsRequest;
use App\Http\Requests\UpdateSectionsRequest;
use App\Models\Library;
use App\Models\Sections;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $filters = QueryService::filters($request);

        $libraryId = $user->isSuperAdmin() || $user->isAdmin()
            ? $request->query('library_id', $request->query('id'))
            : $user->librarian?->library_id;

        // Complete Filter
        $filters = array_merge($filters, [
            'library_id' => $libraryId,
        ]);

        $sections = CacheService::remember(
            CacheService::SECTIONS,
            $filters,
            now()->addHour(),
            function () use ($filters) {
                $section = Sections::query();
                $section->with(['librarian:id,name']);

                // ============== Search ============
                $section->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'LIKE', "%$s%")));

                // ============== Search ============
                $section->when(! empty($filters['library_id']), fn ($q, $f) => $q->where('library_id', $filters['library_id']));

                return $section
                    ->orderBy($filters['sort'], $filters['order'])
                    ->paginate(
                        $filters['per_page'],
                        ['*'],
                        'page',
                        $filters['page']
                    );
            }
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Sections retrieved successfully',
            'data' => $sections,
        ], 200);
    }

    public function temporaryIndex(Request $request)
    {
        $library_id = $request->input('library_id');

        $sections = CacheService::remember(
            CacheService::SECTIONS,
            ['library_id' => $library_id],
            now()->addHour(),
            fn () => Sections::query()
                ->with(['library', 'librarian']) // or 'library' if it's a belongsTo
                ->when($library_id, fn ($q) => $q->where('library_id', $library_id))
                ->get()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Sections retrieved successfully',
            'data' => $sections,
        ], 200);
    }

    public function deleted(Request $request)
    {
        $user = $request->user();
        $libraryId = $user->isSuperAdmin()
            ? $request->query('library_id', $request->query('id'))
            : $user->librarian?->library_id;

        $sections = CacheService::remember(
            CacheService::SECTIONS,
            [
                'deleted' => true,
                'is_super_admin' => $user->isSuperAdmin(),
                'library_id' => $libraryId,
            ],
            now()->addHour(),
            fn () => Sections::onlyTrashed()
                ->select(['id', 'name', 'library_id', 'librarian_id', 'created_at', 'updated_at', 'deleted_at'])
                ->where('library_id', $libraryId)
                ->get()
        );

        return $this->response(
            'success',
            'Deleted sections retrieved successfully',
            $sections->toArray(),
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionsRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $library = Library::findOrFail($data['library_id']);
            $data['librarian_id'] = $data['librarian_id'] ?? $library->library_head_id;
            $section = Sections::create($data)->load('librarian.user');

            DB::commit();
            CacheService::invalidate(CacheService::SECTIONS);

            return $this->response(
                'success',
                'Section created successfully',
                $section->toArray(),
                201
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionsRequest $request, Sections $section)
    {
        DB::beginTransaction();
        try {
            $section->update($request->validated());
            $section->load('librarian.user');

            DB::commit();
            CacheService::invalidate(CacheService::SECTIONS);

            return $this->response(
                'success',
                'Section updated successfully',
                $section->toArray(),
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sections $section)
    {
        $this->authorize('delete', $section);

        DB::beginTransaction();
        try {
            $section->delete();

            DB::commit();
            CacheService::invalidate(CacheService::SECTIONS);

            return $this->response(
                'success',
                'Section deleted successfully',
                null,
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

    }

    public function restore(int $section)
    {
        $section = Sections::withTrashed()->findOrFail($section);
        $this->authorize('restore', $section);

        DB::beginTransaction();
        try {
            $section->restore();

            DB::commit();
            CacheService::invalidate(CacheService::SECTIONS);

            return $this->response(
                'success',
                'Section restored successfully',
                $section->toArray(),
                200
            );
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
