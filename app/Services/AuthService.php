<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Campus;

class AuthService
{
    /**
     * Returns User Information
     */
    public function index()
    {
        $user = auth('api')->user();
        $campus = null;
        $branch = null;

        $data = [
            ...$user->toArray(),
        ];

        switch ($user->role) {
            case 'super admin':
                break;

            case 'admin':
            case 'librarian':
                $librarian = $user->librarian;

                $branch = Branch::where('id', '=', $librarian->branch->id)->first();
                $campus = Campus::where('id', '=', $librarian->branch->campus->id)->first();

                // Remove nested relationships before converting user to array
                $user->librarian->unsetRelation('branch');
                break;

            case 'patron':
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
