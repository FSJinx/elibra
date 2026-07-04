<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\JWTGuard;

abstract class Controller
{
    protected function auth(): JWTGuard
    {
        /** @var JWTGuard $auth */
        $auth = auth('api');

        return $auth;
    }

    protected function response(?string $status = null, ?string $message = null, ?array $data = null, int $statusCode = 200)
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
