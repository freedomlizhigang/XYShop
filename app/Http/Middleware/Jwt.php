<?php

namespace App\Http\Middleware;

use App\Models\User\User;
use Closure;

class Jwt
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
        // 反向解析token
        $token = $request->token;
        try {
            $token_arr = explode('.', decrypt($token));
        } catch (\Exception $e) {
            return response()->json(['code'=>2,'msg' => 'Token解析错误！']);
        }
        $uid = $token_arr[0];
        $jwtkey = $token_arr[1];
        $overtime = $token_arr[2];
        // 先判断时间及key的正确性
        if($overtime <= time())
        {
            return response()->json(['code'=>0,'message' => 'Token过期！']);
        }
        if($jwtkey != config('jwt.jwt-key'))
        {
            return response()->json(['code'=>0,'message' => 'Key失效！']);
        }
        // 查有没有这个用户，及用户状态
        $user = User::where('id',$uid)->where('status',1)->first();
        if (is_null($user)) {
            return response()->json(['code'=>0,'message' => '用户已经被删除或禁用，请联系管理员！']);
        }
        // 用户token跟这个token是否一样，单点登录
        if ($user->token != $token) {
            return response()->json(['code'=>0,'message' => '用户在其它手机上登录过！']);
        }
        return $next($request);
    }
}
