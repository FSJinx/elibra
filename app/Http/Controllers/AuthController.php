<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();

        if ($user?->role == 'librarian') {
            $sub_roles = [
                'primary_role' => $user->librarian->primary_role,
            ];

            $campus = [
                'id' => $user->librarian->branch->campus->id,
                'name' => $user->librarian->branch->campus->name,
                'code' => $user->librarian->branch->campus->code,
            ];

            $branch = [
                'id' => $user->librarian->branch->id,
                'name' => $user->librarian->branch->name,
                'contact_info' => $user->librarian->branch->contact_info,
                'email' => $user->librarian->branch->email,
                'email_verified_at' => $user->librarian->branch->email_verified_at,
                'opening_hour' => $user->librarian->branch->opening_hour,
                'closing_hour' => $user->librarian->branch->closing_hour,
            ];

            $user->librarian->unsetRelation('branch');

            return response()->json(['user' => $user, 'campus' => $campus, 'branch' => $branch, 'sub_roles' => $sub_roles]);
        } else {
            return response()->json(['message' => 'You are not logged in.'], 403);
        }

    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        /** @var StatefulGuard $guard */
        $guard = Auth::guard('api');

        if (! $token = $guard->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'status' => 'success',
            'token_type' => 'Bearer',
            'message' => 'Login successful',
        ]);
    }
}
