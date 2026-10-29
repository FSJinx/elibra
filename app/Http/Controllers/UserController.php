<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\User;
use App\Services\CacheService;
use App\Services\QueryService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        private UserService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $this->user();
        $filters = QueryService::filters($request);
        $filters = array_merge(
            $filters,
            [
                'role' => $request->query('role'),
            ],
        );

        return $this->response(data: $this->service->index($filters, $user));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreUsersRequest $request)
    {
        $user = $this->user();

        try {
            $data = $request->validated();

            if ($user->isAdmin() || $user->isLibrarian()) {
                $data = array_merge($request->all(), [
                    'campus_id' => $user->campus_id,
                    'library_id' => $user->librarian->library_id,
                ]);
            }

            $data = array_merge($data, [
                'password' => 'elibra2026',
            ]);

            return $this->response(message: 'User added succesfully!', data: $this->service->create($data), statusCode: 200);
        } catch (Throwable $e) {
            throw $e;
        }
    }

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

    public function approveRegistration(Request $request, User $user)
    {
        $this->authorizeUserAccess($request, $user);
        $this->ensureRegistrationPending($user);

        $user->update([
            'registration' => 'approved',
            'status' => 'active',
        ]);
        CacheService::invalidate(CacheService::USERS);

        return $this->response('success', 'User registration approved successfully.', $user->fresh(['campus', 'patron.program']));
    }

    public function rejectRegistration(Request $request, User $user)
    {
        $this->authorizeUserAccess($request, $user);
        $this->ensureRegistrationPending($user);

        $user->update([
            'registration' => 'rejected',
            'status' => 'inactive',
        ]);
        CacheService::invalidate(CacheService::USERS);

        return $this->response('success', 'User registration rejected.', $user->fresh(['campus', 'patron.program']));
    }

    private function authorizeUserAccess(Request $request, User $target): void
    {
        $reviewer = $request->user();
        $reviewerCampusId = $reviewer->campus_id;
        if ($reviewer->isLibrarian()) {
            $reviewerCampusId = $reviewer->librarian?->library?->campus_id;
        }

        abort_unless(
            $reviewer->isSuperAdmin()
            || (
                ($reviewer->isAdmin() || $reviewer->isLibrarian())
                && $reviewerCampusId !== null
                && $target->campus_id !== null
                && (int) $reviewerCampusId === (int) $target->campus_id
            ),
            403,
            'You are not authorized to manage this user.'
        );
    }

    private function ensureRegistrationPending(User $user): void
    {
        if ($user->registration !== 'pending') {
            throw ValidationException::withMessages([
                'registration' => ['This user does not have a pending registration.'],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorizeUserAccess($request, $user);

        if ((int) $request->user()->id === (int) $user->id) {
            throw ValidationException::withMessages([
                'user' => ['You cannot delete your own account.'],
            ]);
        }

        if ($user->isSuperAdmin() && ! $request->user()->isSuperAdmin()) {
            abort(403, 'You are not authorized to delete a super administrator.');
        }

        $user->delete();
        CacheService::invalidate(CacheService::USERS);

        return $this->response('success', 'User record deleted successfully.');
    }
}
