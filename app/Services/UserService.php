<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private LibrarianService $librarianService,
        private PatronService $patronService
    ) {}

    // ENCAPSULATES
    private const ROLE_PERMISSIONS = [
        'admin' => [
            'user.create',
            'user.view',
            'user.update',
            'user.delete',

            'branch.create',
            'branch.view',
            'branch.update',
            'branch.delete',

            'branchSection.create',
            'branchSection.view',
            'branchSection.update',
            'branchSection.delete',

            'department.create',
            'department.view',
            'department.update',
            'department.delete',

            'program.create',
            'program.view',
            'program.update',
            'program.delete',

            'subscription.credential.create',
            'subscription.credential.view',
            'subscription.credential.update',
            'subscription.credential.delete',

            'academic.create',
            'academic.view',
            'academic.update',
            'academic.delete',

            'serial.create',
            'serial.view',
            'serial.update',
            'serial.delete',

            'book.create',
            'book.view',
            'book.update',
            'book.delete',
        ],
        'librarian' => [
            'book.create',
            'book.view',
            'book.update',
            'book.delete',

            'borrow.create',
            'borrow.view',
            'borrow.update',
        ],
        'patron' => [
            'user.update',
            'user.view',
        ],
    ];

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $librarian = null;
            $patron = null;

            $user = User::create(Arr::only($data, [
                // Personal Information
                'last_name',
                'first_name',
                'middle_initial',
                'sex',
                'birthdate',

                //  Account Info
                'role',
                'contact_number',
                'email',
                'email_verified_at',
                'username',
                'password',

                //  States
                'status',
                'login_attempts',
                'registration',

                //  Foreign Keys
                'profile_picture_id',
                'campus_id',
            ]));

            if ($user->role === 'admin' || $user->role === 'librarian') {
                $librarian = $this->librarianService->store($user, $data);
            } elseif ($user->role === 'patron') {
                $patron = $this->patronService->store($user, $data);
            }

            CacheService::invalidate(CacheService::USERS);

            return [
                'user' => $user,
                'librarian' => $librarian,
                'patron' => $patron,
            ];
        });
    }

    public function index(array $filters, User $user)
    {
        $isSuperAdmin = $user->isSuperAdmin();
        $campusId = match (true) {
            $isSuperAdmin => null,
            $user->isLibrarian() => $user->librarian?->library?->campus_id,
            default => $user->campus_id,
        };

        return CacheService::remember(
            CacheService::USERS,
            array_merge($filters, [
                'campus_id' => $campusId,
                'is_super_admin' => $isSuperAdmin,
            ]),
            now()->addHour(),
            function () use ($filters, $campusId, $isSuperAdmin) {
                $users = User::query();

                if (! $isSuperAdmin) {
                    if ($campusId === null) {
                        $users->whereRaw('1 = 0');
                    } else {
                        $users->where('campus_id', $campusId);
                    }
                }

                $users->when(! empty($filters['query']),
                    function ($u) use ($filters) {
                        $query = '%'.$filters['query'].'%';

                        $u->where(function ($q) use ($query) {
                            $q->where('last_name', 'LIKE', $query)
                                ->orWhere('first_name', 'LIKE', $query)
                                ->orWhere('username', 'LIKE', $query)
                                ->orWhere('email', 'LIKE', $query);
                        });
                    }
                );

                $users->when(! empty($filters['role']),
                    function ($u) use ($filters) {
                        $u->where('role', '=', $filters['role']);
                    }
                );

                return $users
                    ->with(['campus', 'library', 'librarian.library', 'patron.program', 'patron.patronType'])
                    ->orderBy($filters['sort'], $filters['order'])
                    ->paginate(
                        $filters['per_page'] ?? 25,
                        ['*'],
                        'page',
                        $filters['page'] ?? 1
                    );
            }
        );
    }

    public static function assignRoleAndPermissions(User $user, string $role): void
    {

        $permissions = self::ROLE_PERMISSIONS[$role] ?? [];

        if (empty($permissions)) {
            return;
        }

        // Prevent injecting unknown role
        if (! array_key_exists($role, self::ROLE_PERMISSIONS)) {
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Role',
                ])
            );
        }

        $permissionIds = Permission::whereIn(
            'permission',
            $permissions
        )->pluck('id');

        $user->permissions()->syncWithoutDetaching($permissionIds);
    }

    public static function syncRolePermissions(User $user, string $role): void
    {
        if (! array_key_exists($role, self::ROLE_PERMISSIONS)) {
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Role',
                ])
            );
        }

        $permissionIds = Permission::whereIn(
            'permission',
            self::ROLE_PERMISSIONS[$role]
        )->pluck('id');

        $user->update([
            'role' => $role,
        ]);

        $user->permissions()->sync($permissionIds);
    }

    /**
     * syncWithoutDetaching => This will add the provided relationship and will keep
     *                         the existing record to AVOID DUPLICATION
     */
    public static function verifyCampus(User $authUser, array &$data): void
    {
        // Check kung admin
        if (! $authUser->isAdmin()) {
            return;
        }

        if (is_null($authUser->campus_id)) {
            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => 'Admin account has no assigned campus.',
                ])
            );
        }

        $data['campus_id'] = $authUser->campus_id;

    }
}
