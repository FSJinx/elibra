<?php

namespace App\Services;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LibrarianService
{
    public function create(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $userData = Arr::only($data, [
                'first_name',
                'last_name',
                'middle_initial',
                'sex',
                'birthdate',
                'contact_number',
                'email',
                'username',
                'password',
            ]);

            $userData['role'] = 'librarian';
            $userData['password'] = Hash::make($userData['password']);
            $userData['sex'] ??= 'male';

            $user = User::create($userData);

            $librarianData = Arr::only($data, [
                'branch_id',
                'role',
                'tools',
            ]);

            $librarianData['user_id'] = $user->id;
            $librarian = Librarian::create($librarianData);

            CacheService::invalidate(CacheService::LIBRARIANS);

            return [
                'user' => $user->fresh(),
                'librarian' => $librarian->fresh(),
            ];
        });
    }

    public function update(Librarian $librarian, array $data): array
    {
        return DB::transaction(function () use ($librarian, $data) {
            if (! empty(array_intersect(array_keys($data), ['first_name', 'last_name', 'middle_initial', 'sex', 'birthdate', 'contact_number', 'email', 'username', 'password']))) {
                $userData = Arr::only($data, [
                    'first_name',
                    'last_name',
                    'middle_initial',
                    'sex',
                    'birthdate',
                    'contact_number',
                    'email',
                    'username',
                    'password',
                ]);

                if (isset($userData['password'])) {
                    $userData['password'] = Hash::make($userData['password']);
                }

                $librarian->user()->update($userData);
            }

            $librarianData = Arr::only($data, [
                'branch_id',
                'role',
                'tools',
            ]);

            if (! empty($librarianData)) {
                $librarian->update($librarianData);
            }

            CacheService::invalidate(CacheService::LIBRARIANS);

            return [
                'user' => $librarian->user()->first()->fresh(),
                'librarian' => $librarian->fresh(),
            ];
        });
    }

    public function delete(Librarian $librarian): bool
    {
        return DB::transaction(function () use ($librarian) {
            $deleted = $librarian->delete();

            if ($deleted && $librarian->user) {
                $librarian->user->delete();
            }

            CacheService::invalidate(CacheService::LIBRARIANS);

            return $deleted;
        });
    }
}
