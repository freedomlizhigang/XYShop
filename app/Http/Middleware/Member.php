<?php

namespace App\Http\Middleware;

use App\Models\User\User;
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
            return redirect(url('login'))->with('message','请先登录！');
        }
        else
        {
            if (session()->has('nophone') && !is_null(User::where('id',session('member')->id)->value('username'))) {
                session()->forget('nophone');
            }
            return $next($request);
        }
    }
}
