<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramsRequest;
use App\Http\Requests\UpdateProgramsRequest;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $search = trim($request->query('query', ''));
        $sort = $request->query('sort', []);
        $order = strtolower($request->query('order', 'asc'));

        $campusId = null;
        $departmentId = null;

        if ($user) {
            if (!$user->isSuperAdmin()) {
                $campusId = $user->campus_id;
            } else {
                if ($request->filled('campus_id')) {
                    $campusId = $request->campus_id;
                }

                if ($request->filled('department_id')) {
                    $departmentId = $request->department_id;
                }
            }
        }

        $programs = CacheService::remember(
            CacheService::PROGRAMS,
            [
                'campus_id' => $campusId,
                'department_id' => $departmentId,
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
            ],
            now()->addMinutes(10),
            function () use ($search, $sort, $order, $campusId, $departmentId){

                $allowedSortFields = ['name', 'code' ];
                $query = Programs::query();

                if (!is_null($campusId)) {
                    $query->whereHas('department', function ($q) use ($campusId) {
                        $q->where('campus_id', $campusId);
                    });
                }
                if (!is_null($departmentId)) {
                    $query->where('department_id', $departmentId);
                }

                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }

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
                    return $query->get();
            }
        );

         if($programs->isEmpty()){
            return $this->response(
                'error',
                'Program not found.',
                [],
                200
            );
        }

        return $this->response(
            'success',
            'Programs retrieved successfully',
            $programs->toArray(),
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
    public function store(StoreProgramsRequest $request)
    {
        DB::beginTransaction();
        try {

            $program = Programs::create($request->validated());

            DB::commit();

            CacheService::invalidate(CacheService::PROGRAMS);

            return $this->response(
                'success',
                'Program successfully created',
                $program->toArray(),
                201,
            );

        } catch(Throwable $e){
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Programs $programs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Programs $programs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProgramsRequest $request, Programs $programs)
    {
        DB::beginTransaction();
        try{

            $programs->update($request->validated());

            DB::commit();

            CacheService::invalidate(CacheService::PROGRAMS);

            return $this->response(
                'success',
                'Programs updated successfully',
                $programs->toArray(),
                200
            );

        }catch (Throwable $e){
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programs $programs)
    {
        $this->authorize('delete', $programs);

        DB::beginTransaction();
        try {
            $programs->delete();

            DB::commit();

            CacheService::invalidate(CacheService::PROGRAMS);

            return $this->response(
                'success',
                'Selected program was sucessfully deleted',
                [], 
                200
            );

        } catch(Throwable $e){
            DB::rollBack();

            throw $e;
        }
    }
}
