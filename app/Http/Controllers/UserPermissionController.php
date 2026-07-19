<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPermissionRequest;
use App\Http\Requests\UpdateUserPermissionRequest;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use App\Services\UserPermissionService;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = UserPermissionService::view($request->user());

        return $this->response(
            'success',
            'User Permissions retrieved!',
            $permissions->toArray()
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
    public function store(StoreUserPermissionRequest $request)
    {
        $validated = $request->validated();

        $userPermission = UserPermissionService::assign(
            $request->user(),
            User::findOrFail($validated['user_id']),
            Permission::findOrFail($validated['permission_id'])
        );

        return $this->response(
            'success',
            'User permission created successfully',
            $userPermission->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(UserPermission $userPermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserPermission $userPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserPermissionRequest $request, UserPermission $userPermission)
    {
        $validated = $request->validated();

        $userPermission = UserPermissionService::update(
            $request->user(),
            $userPermission,
            Permission::findOrFail($validated['permission_id'])
        );

        return $this->response(
            'success',
            'User Permission update successfully',
            $userPermission->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPermission $userPermission)
    {
        UserPermissionService::delete(
            request()->user(),
            $userPermission
        );

        return $this->response(
            'success',
            'User Permission deleted successfully.',
            [],
            200
        );
    }
}
