<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function index(AuthService $authService)
    {
        $user = $this->auth()->user();

        if (! $user) {
            return $this->response('error', 'You are not logged in.');
        }

        return $this->response(data: $authService->index());
    }

    public function refresh()
    {
        try {
            // Get user data with the current valid token BEFORE refreshing
            $authService = app(AuthService::class);
            $userData = $authService->index();

            // Now refresh the token
            $token = $this->auth()->refresh(true, true);

            return $this->response('success', data: [
                'token' => $token,
                'token_type' => 'bearer',
                'user' => $userData,
            ]);
        } catch (JWTException $e) {
            return $this->response('error', 'Session expired, please login again.', statusCode: 401);
        }
    }

    public function logout()
    {
        if ($this->auth()->check()) {
            $this->auth()->logout();
        }

        return $this->response('success', 'Logged out succesfully.');
    }
}
