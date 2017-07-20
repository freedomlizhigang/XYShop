<?php

namespace App\Http\Middleware;

use Closure;

class Member
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
        if(is_null(session('member')))
        {
            return redirect('/user/login')->with('message','请先登录！');
        }
        else
        {
            return $next($request);
        }
    }
}
