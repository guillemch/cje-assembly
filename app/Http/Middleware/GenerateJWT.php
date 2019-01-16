<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use JWTAuth;
use View;

class GenerateJWT
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
        $user = Auth::user();

        $jwtToken = ($user) ? JWTAuth::fromUser($user) : '';

        View::share('jwtToken', $jwtToken);
        return $next($request);
    }
}
