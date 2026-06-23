<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function index()
    {
        $user = $this->auth()->user();

        if (! $user) {
            return response()->json(['message' => 'You are not logged in.'], 401);
        }

        $data = [
            // Personal Info
            'last_name' => $user->last_name,
            'middle_initial' => $user->middle_initial ?? 'N/A',
            'first_name' => $user->first_name,
            'sex' => $user->sex,
            'birthdate' => $user->birthdate ? date('F d, Y', strtotime($user->birthdate)) : null,

            // Contact Info
            'contact_number' => $user->contact_number,
            'email' => $user->email,
            'username' => $user->username,

            // Account Info
            'email_verified_at' => $user->email_verified_at,
            'profile_picture' => $user->profile_photos?->path ? asset('storage/'.$user->profile_photos?->path) : null,
            'status' => $user->status == 0 ? 'Active' : ($user->status == 1 ? 'Suspended' : 'Expired'),
            'role' => $user->role,

            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        if ($user?->role == 'librarian') {
            $library = [
                'role' => [
                    'primary_role' => $user->librarian->primary_role->name,
                    'secondary_roles' => [] ?? null,
                ],

                'campus' => [
                    'name' => $user->librarian->branch->campus->name,
                    'code' => $user->librarian->branch->campus->code,
                ],

                'branch' => [
                    'id' => $user->librarian->branch->id,
                    'name' => $user->librarian->branch->name,
                    'contact_info' => $user->librarian->branch->contact_info,
                    'email' => $user->librarian->branch->email,
                    'email_verified_at' => $user->librarian->branch->email_verified_at,
                    'opening_hour' => $user->librarian->branch->opening_hour,
                    'closing_hour' => $user->librarian->branch->closing_hour,
                ],
            ];

            $data += [
                'library' => $library,
            ];

            $user->librarian->unsetRelation('branch');

            return $this->response(data: $data);
        } else if ($user?->role === 'patron') {

        }

        return $this->response(data: $data);
    }

    public function refresh()
    {
        try {
            $token = $this->auth()->refresh(true, true);

            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            return $this->response('error', 'Session expired, please login again.', statusCode: 401);
        }
    }
}
