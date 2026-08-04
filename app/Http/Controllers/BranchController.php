<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Services\CacheService;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        [
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
            'page' => $page,
            'per_page' => $perPage,
        ] = QueryService::filters($request);

        $campusId = null;
        $isGuest = is_null($user);

        $campusId = ($user && !$user->isSuperAdmin())
            ? $user->campus_id
            : null;

        $branches = CacheService::remember(
            CacheService::BRANCHES,
            [
                'campus_id' => $campusId,
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
                'page' => $page,
                'per_page' => $perPage,
            ],
            now()->addHour(),
            function () use ($isGuest, $campusId, $search, $sort, $order, $perPage) {

                $allowedSortFields = [
                    'name',
                    'email',
                    'opening_hour',
                    'created_at',
                ];

                $query = Branch::query();

                // Admin: only branches from their campus
                if (!is_null($campusId)) {
                    $query->where('campus_id', $campusId);
                } elseif ($isGuest) {
                    
                        $query->select([
                            'name',
                            'contact_info',
                            'email',
                            'opening_hour',
                            'closing_hour',
                            'logo_id',
                            'branch_head_id',
                            'campus_id',
                        ]);

                }

                // Search
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('contact_info', 'LIKE', "%{$search}%");
                    });
                }

                // Sort
                if (is_array($sort) && !empty($sort)) {
                    foreach ($sort as $field) {
                        if (in_array($field, $allowedSortFields, true)) {
                            $query->orderBy(
                                $field,
                                $order === 'desc' ? 'desc' : 'asc'
                            );
                        }
                    }
                } else {
                    $query->orderBy('name');
                }

                return $query->paginate($perPage);
            }
        );

        return $this->response(
            'success',
            'Branches retrieved successfully',
            $branches->toArray(),
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
    public function store(StoreBranchRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if($request->user()->isAdmin()){
                $data['campus_id'] = $request->user()->campus_id;
            }

            $branch = Branch::create($data);

            DB::commit();
            CacheService::invalidate(CacheService::BRANCHES);

            return $this->response(
                'success',
                'Branch created successfully',
                $branch->toArray(),
                201
            );

        } catch(Throwable $e) {
            DB::rollBack();

            throw $e;
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        DB::beginTransaction();
        try{
            $branch->update($request->validated());

            DB::commit();

            CacheService::invalidate(CacheService::BRANCHES);

            return $this->response(
                'success',
                'Branch updated successfully',
                $branch->toArray(),
                200
            );
        } catch (Throwable $e){
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);
        
        DB::beginTransaction();
        try{

            $branch->delete();
            DB::commit();

            CacheService::invalidate(CacheService::BRANCHES);
            CacheService::invalidate(CacheService::SECTIONS);
            CacheService::invalidate(CacheService::BRANCH_SECTIONS);

            return $this->response(
                'success',
                'Branch deleted successfully',
                null,
                200
            );
        } catch(Throwable $e){
            DB::rollBack();
            throw $e;
        }
    

    }
}
