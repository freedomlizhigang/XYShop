<?php

namespace App\Http\Middleware;

use App\Models\Log;
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
        if(is_null(session('user')))
        {
            return redirect('/console/login')->with('message','请先登录！');
        }

        // 拼接权限名字，url的第二个跟第三个参数
        $toArr = explode('/',$request->path());
        // 如果不写方法名，默认为index
        $toArr[2] = count($toArr) == 2 ? 'index' : $toArr[2];
        $priv = $toArr[1].'-'.$toArr[2];
        // 取当前用户
        $user = session('user');
        // 在这里进行一部分权限判断，主要是判断打开的页面是否有权限
        if(in_array(1,$user->allRole) || in_array($priv,$user->allPriv))
        {
            // 日志记录，只记录post或者del操作(通过比较url来得出结果)
            if ($request->isMethod('post') || substr_count($toArr[2],'del') > 0 || substr_count($toArr[2],'status') > 0) {
                $url = $request->getRequestUri();
                Log::create(['admin_id'=>$user->id,'url'=>$url,'user'=>$user->name,'created_at'=>date('Y-m-d H:i:s')]);
            }
            $respond = $next($request);
            return $respond;
        }
        else
        {
            return back();
        }
    }
}
