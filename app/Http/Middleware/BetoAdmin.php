<?php

namespace App\Http\Middleware;

use App\Models\Console\Log;
use Auth;
use Closure;

class BetoAdmin
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
        if(is_null(session('console')))
        {
            return redirect('/console/login')->with('message','请先登录！');
        }

        // 拼接权限名字，url的第二个跟第三个参数
        $toArr = explode('/',$request->path());
        if ($toArr[0] != 'console') {
            return back()->with('message','没有权限！');
        }
        // 如果不写方法名，默认为index
        $toArr[2] = count($toArr) == 2 ? 'index' : $toArr[2];
        $priv = $toArr[1].'-'.$toArr[2];
        // 取当前用户
        $user = session('console');
        // 在这里进行一部分权限判断，主要是判断打开的页面是否有权限
        if(in_array(1,$user->allRole) || in_array($priv,$user->allPriv))
        {
            // 日志记录，只记录post或者del操作(通过比较url来得出结果)
            Log::create(['admin_id'=>$user->id,'method'=>$request->method(),'url'=>$request->fullUrl(),'user'=>$user->name,'data'=>json_encode($request->all()),'created_at'=>date('Y-m-d H:i:s')]);
            $respond = $next($request);
            return $respond;
        }
        else
        {
            return back();
        }
    }
}
