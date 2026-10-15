<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Throwable;

class RegisterController extends Controller
{
    public function index(RegisterRequest $request, AuthService $authService)
    {
        try {
            $authService->create($request->validated());

            return $this->response(message: 'Account registered successfully. Please wait for approval before logging in or using your account', statusCode: 200);
        } catch (Throwable $e) {
            return $this->response('error', $e->getMessage());
        }
    }
}
