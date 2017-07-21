<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Admin;
use App\Models\Priv;
use App\Models\RoleUser;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function getLogin()
    {
        if(\Session::has('console')){return redirect('/console/index/index');}
        return view('admin.login');
    }
    /**
     * 登录提交数据验证功能，成功后跳转到后台首页
     * @param  Request $request [description]
     */
    public function postLogin(Request $res)
    {
        if(\Session::has('console')){return redirect('/console/index/index');}

        $username = $res->input('name');
        $pwd = $res->input('password');
        $user = Admin::where('status',1)->where('name',$username)->first();
        if (is_null($user)) {
            return back()->with('message','用户不存在或已被禁用！');
        }
        else
        {
            if ($user->password != app('com')->makepwd($pwd,$user->crypt)) {
                return back()->with('message','密码不正确！');
            }
            // 查出所有用户权限并存储下来
            $allRole = RoleUser::where('user_id',$user->id)->pluck('role_id')->toArray();
            $user->allRole = $allRole;
            $allPriv = Priv::whereIn('role_id',$allRole)->pluck('label');
            $user->allPriv = $allPriv->unique()->toArray();
            \Session::put('console',$user);
            return redirect('/console/index/index');
        }
    }
    /**
     * 自写logout，实现登出后的跳转页面控制
     */
    public function getLogout()
    {
        \Session::put('console',null);
        return redirect('/console/login');
    }
}
