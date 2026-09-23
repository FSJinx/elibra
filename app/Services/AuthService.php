<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Campus;
use App\Models\User;

class AuthService
{
    /**
     * Returns User Information
     */
    public function index(): array
    {
        /** @var User|null $user */
        $user = auth('api')->user();

        if (! $user instanceof User) {
            return [];
        }

        $campus = null;
        $branch = null;

        $data = [
            ...$user->toArray(),
        ];

        switch ($user?->role) {
            case 'super_admin':
                break;

            case 'admin':
            case 'librarian':
                $librarian = $user->librarian;

                if ($librarian) {
                    $branch = Branch::where('id', '=', $librarian->branch->id)->first();
                    $campus = Campus::where('id', '=', $librarian->branch->campus->id)->first();

                    // Remove nested relationships before converting user to array
                    $user->librarian->unsetRelation('branch');
                } else {
                    $campus = Campus::where('id', '=', $user->campus_id)->first();
                }
                break;

            case 'patron':
                $user->loadMissing(['patron.program', 'patron.patronType', 'campus']);
                $data = [
                    ...$user->toArray(),
                    'patron' => $user->patron,
                ];
                $campus = $user->campus;
                break;

            default:
                break;
        }

        return [
            ...$data,
            'campus' => $campus,
            'branch' => $branch,
        ];
    }
}
