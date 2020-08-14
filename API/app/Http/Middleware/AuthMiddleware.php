<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if(!$token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        }
        catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        }
        catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        return $next($request);
    }
}
