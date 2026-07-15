<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $role = $request->role; // Tiyakin na walang space ang pagpasa nito mula sa Vue ('librarian')

        // I-eager load ang malalalim na relasyon para iwas sa N+1 query problem
        $users = User::query()->with(['librarian.branch.campus', 'patron.program.department.campus']);

        if ($user->role === 'librarian') {
            $campusId = $user->librarian?->branch?->campus_id;

            if (! $campusId) {
                return response()->json(['status' => 'error', 'message' => 'Librarian has no assigned campus.'], 400);
            }

            // 1. I-GROUP NATIN ANG CAMPUS FILTER GAMIT ANG 'OR'
            $users->where(function ($mainQuery) use ($campusId) {
                // Option A: Kung siya ay librarian sa campus na ito
                $mainQuery->whereHas('librarian.branch', function ($query) use ($campusId) {
                    $query->where('campus_id', $campusId);
                })
                // Option B: O kaya naman siya ay patron sa campus na ito
                    ->orWhereHas('patron.program.department', function ($query) use ($campusId) {
                        $query->where('campus_id', $campusId);
                    });
            });

            // 2. IBALIK NATIN ANG ROLE FILTERING (Para sa dropdown sa frontend)
            if ($role && ($role === 'librarian' || $role === 'patron')) {
                $users->where('role', $role);
            } else {
                // Kung walang piniling role sa filter, ipakita pareho ang mga librarian at patron sa campus na yon (bawal admin)
                $users->where('role', '!=', 'admin');
            }

        } elseif ($user->role === 'admin') {
            // Kung admin, walang campus isolation. Pero pwede pa rin siya mag-filter ng role kung gusto niya.
            if ($role) {
                $users->where('role', $role);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $users->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsersRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'sex' => $request->sex,
                'role' => $request->role,
                'username' => $request->username,
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('elibra2026'),
            ]);

            UserPermissionController::initializePermissions($user);


            DB::commit();

            return $this->response('success', 'User successfully created.', $user->toArray());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->response('error', 'Error creating user.', statusCode: 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsersRequest $request, User $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $users)
    {
        //
    }
}
