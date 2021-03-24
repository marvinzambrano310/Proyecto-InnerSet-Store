<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        return $next($request)
            ->header('Access-Control-Allow-Origin', 'https://proyecto-inner-set-store-olosd.ondigitalocean.app/api')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Accept, X-Requested-With, Content-Type, X-Token-Auth, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
