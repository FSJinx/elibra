<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $this->user();
        $users = null;

        if ($user->isSuperAdmin()) {
            $users = User::all();
        }

        // $users->paginate();

        return $this->response(message: 'All users retrieved successfully!', data: $users->toArray());
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
            $data = $request->validated();

            UserService::verifyCampus($request->user(), $data);

            $role = $data['role'];

            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            UserService::assignRoleAndPermissions($user, $role);

            DB::commit();

            return $this->response(
                'success',
                'User successfully created.',
                $user->toArray(),
                201,
            );

        } catch (Exception $e) {
            DB::rollback();

            return $this->response(
                'error',
                'Failed to create user.',
                [],
                422,
            );
            // throw $e;
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
    public function update(UpdateUsersRequest $request, User $user)
    {

        DB::beginTransaction();
        try {
            $data = $request->validated();

            UserService::verifyCampus($request->user(), $data);

            // Update password if provided from request
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Only Super Admin is allowed to update role
            if (isset($data['role']) && $user->role !== $data['role']) {

                UserService::syncRolePermissions($user, $data['role']);
                // no need to update role, may role na sa service
                unset($data['role']);

            }

            $user->update($data);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully.',
                $user->toArray(),
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return $this->response(
                'error',
                'Failed to update user.',
                [],
                422,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $users)
    {
        //
    }
}
