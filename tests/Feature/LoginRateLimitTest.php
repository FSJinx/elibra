<?php

namespace Tests\Feature;

use App\Services\RateLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginRateLimitTest extends TestCase
{
    public function test_login_rate_limit_is_scoped_to_ip_and_username_and_only_counts_failures(): void
    {
        RateLimitService::register();

        $limiter = RateLimiter::limiter('login');
        $request = Request::create('/api/auth/login', 'POST', ['username' => 'reader'], [], [], [
            'REMOTE_ADDR' => '192.0.2.1',
        ]);
        $limit = $limiter($request);

        $this->assertSame(5, $limit->maxAttempts);
        $this->assertSame(60, $limit->decaySeconds);
        $this->assertNotSame(
            $limit->key,
            $limiter(Request::create('/api/auth/login', 'POST', ['username' => 'another-reader'], [], [], [
                'REMOTE_ADDR' => '192.0.2.1',
            ]))->key
        );
        $this->assertNotSame(
            $limit->key,
            $limiter(Request::create('/api/auth/login', 'POST', ['username' => 'reader'], [], [], [
                'REMOTE_ADDR' => '192.0.2.2',
            ]))->key
        );
        $this->assertTrue(($limit->afterCallback)(response()->json([], 403)));
        $this->assertFalse(($limit->afterCallback)(response()->json([], 200)));
    }
}
