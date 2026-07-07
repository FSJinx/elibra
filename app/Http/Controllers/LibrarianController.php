<?php

namespace App\Http\Controllers;

use App\Models\Librarian;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLibrarianRequest;
use App\Http\Requests\UpdateLibrarianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LibrarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Librarian::with([
            'user',
            'role',
            'branch'
        ])->get();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLibrarianRequest $request)
    {
        $user = $this->user();

        // if($user?->role == 'librarian'){
        //     return $this->response(
        //         'error',
        //         'You are not authorized to perform this action.',
        //         null,
        //         403
        //     );
        // }

        $users = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'librarian',
        ]);

        $librarian = Librarian::create([
            'user_id' => $user->id,
            'branch_id' => $request->branch_id,
            'primary_role_id' => $request->primary_role_id,
            'tools' => $request->tools,
        ]);

        return response()->json([
            'user' => $users,
            'librarian' => $librarian,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Librarian $librarian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Librarian $librarian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibrarianRequest $request, Librarian $librarian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Librarian $librarian)
    {
        //
    }
}
