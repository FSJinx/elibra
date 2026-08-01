<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTGuard;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.auth' => JWTGuard::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {

                // 1. Alamin ang tamang Status Code depende sa exception
                $statusCode = 500; // Default status code

                if ($e instanceof AuthenticationException || str_contains($e->getMessage(), 'expired')) {
                    $statusCode = 401; // Unauthorized / Expired Token
                } elseif ($e instanceof HttpException) {
                    $statusCode = $e->getStatusCode(); // Kukunin ang 404, 403, 405, etc.
                } elseif (method_exists($e, 'getStatusCode')) {
                    $statusCode = $e->getStatusCode();
                }

                // 2. I-return ang response gamit ang DYNAMIC na $statusCode (hindi hardcoded)
                return response()->json([
                    'status' => 'error',
                    'message' => config('app.debug')
                        ? $e->getMessage()
                        : ($statusCode === 500 ? 'Something went wrong.' : $e->getMessage()),
                    'data' => [],
                ], $statusCode);
            }

            return null;
        });
    })->create();
