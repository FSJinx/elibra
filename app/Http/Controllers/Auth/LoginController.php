<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::query()->where('username', '=', $credentials['username'])->orWhere('email', $credentials['username'])->first();

        // Returns if username is not found in the system
        if (! $user) {
            return $this->response('error', data: ['username' => 'User not found.'], statusCode: 403);
        }

        // Returns if user has logged on multiple times
        if ($user && $user->login_attempts == 5) {
            return $this->response('error', 'You reached that maximum failed login attempts. Please contact your administrator to resolve this issue.', statusCode: 423);
        }

        // Successfully logs in user
        if ($user && $token = $this->auth()->attempt($credentials)) {
            $user->login_attempts = 0;
            $user->save();

            return $this->respondWithToken($token);
        }
        // Incorrect password
        else {
            if ($user->role !== 'admin') {
                if ($user->login_attempts != 5) {
                    $user->login_attempts += 1;
                    $user->save();
                }

                if ($user->login_attempts >= 5) {
                    return $this->response('error', 'Due to multiple incorrect attempt, your account has been locked. Please reset your password or contact your administrator.', statusCode: 423);
                }
            }

            return $this->response(
                'error',
                'Remaining attempts: '.(5 - $user->login_attempts),
                data: ['password' => 'Wrong password, please try again.'],
                statusCode: 403
            );
        }
    }
}
