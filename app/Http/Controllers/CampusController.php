<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampusRequest;
use App\Http\Requests\UpdateCampusRequest;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('query');
        $sort = $request->query('sort', []);
        $order = $request->query('order');

        $campus = Campus::query();

        if ($search) {
            $campus->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('code', 'LIKE', '%'.$search.'%')
                ->orWhere('address', 'LIKE', '%'.$search.'%');
        }

        if ($sort && is_array($sort)) {
            foreach ($sort as $field) {
                $campus->orderBy($field, $order ?? 'asc');
            }
        }

        return json_encode($campus->get(['*']));
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
    public function store(StoreCampusRequest $request)
    {
        $this->authorize('create', Campus::class);

        $campus = Campus::create($request->validated());

        return $this->response(
            'success',
            'Campus created successfully',
            $campus->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Campus $campus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campus $campus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampusRequest $request, Campus $campus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus)
    {
        //
    }
}
