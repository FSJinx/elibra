<?php

namespace App\Services;

use App\Models\Patron;
use App\Models\PatronType;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatronService
{
    public function generateEBC(Patron $patron)
    {
        if (! $patron->user_id || ! $patron->patron_type_id)  {
            return null;
        }

        $patronKey = strtoupper($patron->patronType->key . now()->format('Y'));
        $lastCode = Patron::query()
            ->where('ebc_number', 'LIKE', $patronKey.'%')
            ->lockForUpdate()
            ->pluck('ebc_number')
            ->map(fn (string $ebc) => (int) str_replace($patronKey, '', $ebc))
            ->max() ?? 0;

        return $patronKey.str_pad((string) ($lastCode + 1), 6, '0', STR_PAD_LEFT);
    }

    public function store(User $user, array $data): Patron
    {
        return DB::transaction(function () use ($user, $data) {
            $patron = Patron::create([
                'ebc_number' => null,

                'external_organization' => $data['external_organization'] ?? null,
                'date_joined' => $data['date_joined'] ?? now() ?? null,
                'account_expiry' => null,
                'remarks' => $data['remarks'] ?? null,
                'user_id' => $user->id,
                'patron_type_id' => $data['patron_type_id'] ?? null,
                'program_id' => $data['program_id'] ?? null,
            ]);

            $type = PatronType::find($data['patron_type_id']);

            $patron->update([
                'ebc_number' => $this->generateEBC($patron),
                'account_expiry' => now()->addYears($type->validity_period),
            ]);

            return $patron;
        });
    }

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

            $userData['role'] = 'patron';
            $userData['password'] = Hash::make($userData['password']);
            $userData['sex'] ??= 'male';

            $user = User::create($userData);

            $patronData = Arr::only($data, [
                'ebc_number',
                'external_organization',
                'date_joined',
                'account_expiry',
                'remarks',
                'patron_type_id',
                'department_id',
                'program_id',
            ]);

            $patronData['user_id'] = $user->id;
            $patron = Patron::create($patronData);

            CacheService::invalidate(CacheService::PATRONS);

            return [
                'user' => $user->fresh(),
                'patron' => $patron->fresh(),
            ];
        });
    }

    public function update(Patron $patron, array $data): array
    {
        return DB::transaction(function () use ($patron, $data) {
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

                $patron->user()->update($userData);
            }

            $patronData = Arr::only($data, [
                'ebc_number',
                'external_organization',
                'date_joined',
                'account_expiry',
                'remarks',
                'patron_type_id',
                'program_id',
            ]);

            if (! empty($patronData)) {
                $patron->update($patronData);
            }

            CacheService::invalidate(CacheService::PATRONS);

            return [
                'user' => $patron->user()->first()->fresh(),
                'patron' => $patron->fresh(),
            ];
        });
    }

    public function delete(Patron $patron): bool
    {
        return DB::transaction(function () use ($patron) {
            $deleted = $patron->delete();

            if ($deleted && $patron->user) {
                $patron->user->delete();
            }

            CacheService::invalidate(CacheService::PATRONS);

            return $deleted;
        });
    }
}
