<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $redirect = 'xyshop' == $guard ? '/console/index/index' : '/home';
        if (Auth::guard($guard)->check()) {
            return redirect($redirect);
        }
        return $next($request);
    }
}
