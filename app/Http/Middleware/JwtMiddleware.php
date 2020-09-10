<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            try {
                $refreshed_token = JWTAuth::refresh(JWTAuth::getToken());
                $user = JWTAuth::setToken($refreshed_token)->toUser();
                return response()->json(['message' => 'token_expired', 'refreshed_token' => $refreshed_token], 401);
            } catch (JWTException $e) {
                return response()->json([
                    'message' => 'token_not_refreshed'
                ], 401);
            }
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'token_invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'token_absent'], 401);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return $next($request);
    }
}
