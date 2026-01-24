<?php

namespace App\Http\Middleware;

use App\Models\Sanctum\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthGuardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['msg' => 'Token not provided', 'success' => false], 401);
        }
        // 查找 token
        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken) {
            return response()->json(['msg' => 'Invalid token', 'success' => false], 401);
        }
        if (empty($guards)) {
            $guards = ['sys_users'];
        }
        Log::info('Guards: ',  $guards);
        Log::info('Auth Providers: ',  config('auth.providers'));
        foreach ($guards as $guard) {
            Log::info('Auth Providers Model: ' . config('auth.providers.' . $guard . '.model'));
            if ($accessToken->tokenable_type == config('auth.providers.' . $guard . '.model')) {
                return $next($request);
            }
        }
        return redirect('/admin/login');
    }
}
