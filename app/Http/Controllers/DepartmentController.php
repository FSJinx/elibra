<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        // Users are restricted to their campus
        if (!$user->isSuperAdmin()) {
            $campusId = $user->campus_id;
        }

        // Super admin can filter campus
        if ($user->isSuperAdmin() && $request->filled('campus_id')) {
            $campusId = $request->campus_id;
        }

        $version = Cache::get('departments_version', 1);

        $cacheKey = 'departments:v'.$version.':'.md5(json_encode([
            'campus_id' => $campusId,
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
        ]));


        $departments = Cache::remember($cacheKey, now()->addMinutes(10), function () use (
            $search,
            $sort,
            $order,
            $campusId
        ) {

            $allowedSortFields = ['name', 'code'];

            $query = Department::query()
                ->when($campusId !== null, function ($query) use ($campusId) {
                    $query->where('campus_id', $campusId);
                });


            // Search
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('code', 'LIKE', '%'.$search.'%');
                });
            }


            // Sorting
            if (is_array($sort)) {
                foreach ($sort as $field) {
                    if (in_array($field, $allowedSortFields, true)) {
                        $query->orderBy(
                            $field,
                            $order === 'desc' ? 'desc' : 'asc'
                        );
                    }
                }
            }


            $departments = $query->get();


            // Levenshtein fallback
            if ($departments->isEmpty() && $search !== '') {

                $departments = Department::when($campusId, function ($query) use ($campusId) {
                        $query->where('campus_id', $campusId);
                    })
                    ->get()
                    ->sortBy(function ($department) use ($search) {
                        return levenshtein(
                            strtolower($search),
                            strtolower($department->name)
                        );
                    })
                    ->take(3)
                    ->values();
            }


            return $departments;
        });

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
            
            Cache::increment('departments_version');

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

            Cache::increment('departments_version');

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

            $department->programs()->delete(); // Delete program, once the related department deleted
            $department->delete();

            DB::commit();

            Cache::increment('departments_version');

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
