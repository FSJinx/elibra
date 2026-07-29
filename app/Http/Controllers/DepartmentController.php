<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DepartmentController extends Controller
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

        if ($user) {
            if (!$user->isSuperAdmin()) {
                $campusId = $user->campus_id;
            } elseif ($request->filled('campus_id')) {
                $campusId = $request->campus_id;
            }
        }

        $departments = CacheService::remember(
            CacheService::DEPARTMENTS,
            [
                'campus_id' => $campusId,
                'search' => $search,
                'sort' => $sort,
                'order' => $order,
            ],
            now()->addMinutes(10),
            function () use ($search, $sort, $order, $campusId){

            $allowedSortFields = ['name', 'code' ];
            $query = Department::query();

            if (!is_null($campusId)) {
                $query->where('campus_id', $campusId);
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

        if($departments->isEmpty()){
            return $this->response(
                'error',
                'Department not found.',
                [],
                200
            );
        }

        return $this->response(
            'success',
            'Departments retrieved successfully',
            $departments->toArray(),
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
    public function store(StoreDepartmentRequest $request)
    {
        DB::beginTransaction();
        try {

            $department = Department::create($request->validated());

            DB::commit();
            
            CacheService::invalidate(CacheService::DEPARTMENTS);

            return $this->response(
                'success',
                'Department successfully created',
                $department->toArray(),
                201,
            );


        } catch(Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        DB::beginTransaction();
        try {

            $department->update($request->validated());

            DB::commit();

            CacheService::invalidate(CacheService::DEPARTMENTS);

            return $this->response(
                'success',
                'Depatment updated successfully.',
                $department->toArray(),
                200,
            );

        } catch (Throwable $e){
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        DB::beginTransaction();
        try {

            $department->delete();

            DB::commit();

            CacheService::invalidate(CacheService::DEPARTMENTS);
            CacheService::invalidate(CacheService::PROGRAMS);

            return $this->response(
                'success',
                'Selected department was successfully deleted.',
                [],
                200
            );

        } catch (Throwable $e){
            DB::rollBack();

            throw $e;
        }
    }
}
