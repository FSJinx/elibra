<?php

namespace App\Http\Controllers;

use App\Models\Patron;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatronRequest;
use App\Http\Requests\UpdatePatronRequest;

class PatronController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return Patron::with([
        'user',
        'patronType'
    ])->get();  
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
    public function store(StorePatronRequest $request)
    {

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role' => 'patron',
    ]);

    $patron = Patron::create([
        'user_id' => $user->id,
        'patron_type_id' => $request->patron_type_id,
        'program_id' => $request->program_id,
        'ebc_number' => $request->ebc_number,
        'remarks' => $request->remarks,
    ]);

    return response()->json([
        'user' => $user,
        'patron' => $patron,
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patron $patron)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patron $patron)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatronRequest $request, Patron $patron)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patron $patron)
    {
        //
    }
}
