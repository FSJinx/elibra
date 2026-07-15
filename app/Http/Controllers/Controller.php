<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tymon\JWTAuth\JWTGuard;

abstract class Controller
{
    use AuthorizesRequests;

    protected function auth(): JWTGuard
    {
        /** @var JWTGuard $auth */
        $auth = auth('api');

        return $auth;
    }

    protected function user(): ?User
    {
        return $this->auth()->user();
    }

    protected function response(?string $status = 'success', ?string $message = null, ?array $data = null, int $statusCode = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function respondWithToken(?string $token)
    {
        return $this->response('success', data: [
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    // protected function recordLog()
    // {

    // }
}
