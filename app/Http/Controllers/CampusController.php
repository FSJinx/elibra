<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampusRequest;
use App\Http\Requests\UpdateCampusRequest;
use Illuminate\Http\Request;

class CampusController extends Controller
{


    public function index(Request $request)
    {
        $search = trim($request->query('query', ''));// Get the search query from the request, default to an empty string if not provided
        $sort = $request->query('sort', []); // Get the sort fields from the request, default to an empty array if not provided
        $order = strtolower($request->query('order', 'asc')); // Get the sort order from the request, default to 'asc' if not provided

        //Allowed column to be sorted
        $allowedSortFields = ['name', 'code'];

        $campus = Campus::query();

        //Normal Search
        if($search){
            $campus->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('address', 'LIKE', '%' . $search . '%');
            });
        }

        //For better Sorting
        if(is_array($sort)){
            foreach($sort as $field){
                if(in_array($field, $allowedSortFields, true)){
                    $campus->orderBy($field, $order === 'desc' ? 'desc' : 'asc');
                }
            }
        }
        
        $campuses = $campus->get(['id', 'name']);
        //We use fuzzy search if the search query is not empty and no campuses were found in the normal search
        /*
            [levenshtein] is used to calculate the distance
            between two strings, and we sort the campuses by the distance
            between the search query and the campus name, 
            and take the top 3 closest matches
        */
        if ($campuses->isEmpty() && $search !== '') {

            $campuses = Campus::select('id', 'name')
                ->get()
                ->sortBy(function ($campus) use ($search) {
                    return levenshtein(
                        strtolower($search),
                        strtolower($campus->name)
                    );
                })
                ->take(3)
                ->values();
        }

        return $this->response(
            'success',
            'Campuses retrieved successfully',
            $campuses->toArray(),
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
        $this->authorize('view', $campus);

        return $this->response(
            'success',
            'Campus retrieved successfully',
            $campus->toArray(),
            200
        );
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
        $this->authorize('update', $campus);

        $campus->update($request->validated());

        return $this->response(
            'success',
            'Campus updated successfully',
            $campus->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus)
    {
        $this->authorize('delete', $campus);

        $campus->delete();

        return $this->response(
            'success',
            'Campus deleted successfully',
            null,
            200
        );
    }
}
