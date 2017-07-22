<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Common\BaseController;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class UserController extends BaseController
{
	// 登录
	public function getLogin()
	{
		if(!is_null(session('member')))
		{
			// 如果上次的页面是登录页面，回首页
			if (strpos(url()->previous(),'/user/login')) {
				return redirect('/')->with('message','您已登录！');
			}
			else
			{
				return redirect(url()->previous())->with('message','您已登录！');
			}
		}
        $ref = url()->previous();
        $info = (object) [];
        $info->pid = 4;
        session()->put('homeurl',url()->previous());
        return view('home.user.login',compact('ref','info'));
	}
	// 登录
    public function postLogin(UserRequest $res)
    {
        if(!is_null(session('member')))
        {
            return redirect(url()->previous())->with('message','您已登录！');
        }
        $username = $res->input('data.username');
        $pwd = $res->input('data.password');
        $user = User::where('status',1)->where('username',$username)->first();
	    if (is_null($user)) {
	    	return back()->with('message','用户不存在或已被禁用！');
	    }
	    else
	    {
		    try {
                if ($pwd != decrypt($user->password)) {
                    return back()->with('message','密码不正确！');
                }       
            } catch (\Exception $e) {
                return back()->with('message','密码不正确！');
            }
            User::where('id',$user->id)->update(['last_ip'=>$res->ip(),'last_time'=>Carbon::now()]);
            // 计算折扣比例
            /*$points = session('member')->points;
            $discount = Group::where('points','<=',$points)->orderBy('points','desc')->value('discount');
            if (is_null($discount)) {
                $discount = Group::orderBy('points','desc')->value('discount');
            }
            $user->discount = $discount;*/
	    	session()->put('member',$user);
            // 更新购物车
            $this->updateCart($user->id);
	    	return redirect($res->ref);
	    }
    }
    // 注册
	public function getRegister()
	{
		if(!is_null(session('member')))
		{
			// 如果上次的页面是登录页面，回首页
			if (strpos(url()->previous(),'/user/register')) {
				return redirect('/')->with('message','您已登录！');
			}
			else
			{
				return redirect(url()->previous())->with('message','您已登录！');
			}
		}
        $ref = url()->previous();
        $info = (object) [];
        $info->pid = 4;
        return view('home.user.register',compact('ref','info'));
	}
	// 注册
    public function postRegister(UserRequest $res)
    {
    	if(!is_null(session('member')))
		{
			return redirect(url()->previous())->with('message','您已登录！');
		}
    	$username = trim($res->input('data.username'));
    	// 查一样有没有重复的用户名
    	$ishav = User::where('username',$username)->first();
    	if (!is_null($ishav)) {
    		return back()->with('message','用户名已经被使用，请换一个再试！');
    	}
    	$pwd = encrypt($res->input('data.passwords'));
    	$email = $res->input('data.email');
    	try {
	    	$user = User::create(['username'=>$username,'password'=>$pwd,'email'=>$email,'last_ip'=>$res->ip(),'last_time'=>Carbon::now()]);
            // 计算折扣比例
            // $user->discount = 100;
	    	session()->put('member',$user);
            // 更新购物车
            $this->updateCart($user->id);
	    	return redirect($res->ref);
    	} catch (\Exception $e) {
    		return back()->with('message','注册失败，请稍候再试！');
    	}
    }
    // 退出登录
    public function getLogout(Request $res)
    {
    	session()->pull('member');
        // 重新生成session_id
        session()->regenerate();
    	return back()->with('message','您已退出登录！');
    }
}
