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
            $token = $this->auth()->refresh(true, true);

            return $this->respondWithToken($token);
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
