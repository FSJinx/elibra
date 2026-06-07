<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        /** @var \Illuminate\Contracts\Auth\StatefulGuard $guard */
        $guard = Auth::guard('api');

        if (!$token = $guard->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Login successful'
        ]);
    }

    public function user()
    {
        return response()->json(
            auth('api')->user()
        );
    }
}

