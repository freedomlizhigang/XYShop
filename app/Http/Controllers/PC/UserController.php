<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Http\Requests\User\UserRequest;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

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
        session()->put('homeurl',url()->previous());
        $seo = ['title'=>'用户登录 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        return view($this->theme.'.user.login',compact('seo','ref'));
	}
	// 登录
    public function postLogin(UserRequest $res)
    {
        if(!is_null(session('member')))
        {
            return redirect(url()->previous())->with('message','您已登录！');
        }
        $username = $res->input('username');
        $pwd = $res->input('password');
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
            } catch (\Throwable $e) {
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
            // $this->updateCart($user->id);
	    	return redirect(session('homeurl'));
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
        session()->put('homeurl',url()->previous());
        $seo = ['title'=>'用户注册 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        return view($this->theme.'.user.register',compact('seo','ref'));
	}
	// 注册
    public function postRegister(UserRequest $res)
    {
    	if(!is_null(session('member')))
		{
			return redirect(url()->previous())->with('message','您已登录！');
		}
    	$username = trim($res->input('username'));
    	// 查一样有没有重复的用户名
    	$ishav = User::where('username',$username)->first();
    	if (!is_null($ishav)) {
    		return back()->with('message','用户名已经被使用，请换一个再试！');
    	}
    	$pwd = encrypt($res->input('password'));
    	$email = $res->input('email');
    	try {
	    	$user = User::create(['username'=>$username,'password'=>$pwd,'email'=>$email,'last_ip'=>$res->ip(),'last_time'=>Carbon::now()]);
            // 计算折扣比例
            // $user->discount = 100;
	    	session()->put('member',$user);
            // 更新购物车
            // $this->updateCart($user->id);
	    	return redirect(session('homeurl'));
    	} catch (\Throwable $e) {
    		return back()->with('message','注册失败，请稍候再试！');
    	}
    }
    // 忘记密码
    public function getForpwd()
    {
        if(!is_null(session('member')))
        {
            // 如果上次的页面是登录页面，回首页
            if (strpos(url()->previous(),'/user/forpwd')) {
                return redirect('/')->with('message','您已登录！');
            }
            else
            {
                return redirect(url()->previous())->with('message','您已登录！');
            }
        }
        $ref = url()->previous();
        session()->put('homeurl',url()->previous());
        $seo = ['title'=>'忘记密码 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        return view($this->theme.'.user.forpwd',compact('seo','ref'));
    }
    // 忘记密码
    public function postForpwd(Request $res)
    {
        if(!is_null(session('member')))
        {
            return redirect(url()->previous())->with('message','您已登录！');
        }
        $username = $res->input('username');
        $email = $res->input('email');
        // 查一样有没有重复的用户名
        $ishav = User::where('username',$username)->where('email',$email)->first();
        if (is_null($ishav)) {
            return back()->with('message','没有找到用户，请确认用户名及邮箱是否正确！');
        }
        try {
            // 发邮件
            $code = str_random(6);
            // 把code保存在密码字段里，省时省力~
            User::where('id',$ishav->id)->update(['password'=>$code]);
            // 发送邮件
            Mail::send('home.emails.forpwd', ['code'=>$code], function($message) use($email){
                $message->to($email)->subject('希夷SHOP修改密码验证码~');
            });
            // 把用户ID保存到session里
            session()->put('tmp_id',$ishav->id);
            return redirect('/user/forpwd2')->with('message','验证码发送成功，请尽快完成修改！');
        } catch (\Throwable $e) {
            dd($e);
            return back()->with('message','验证码发送失败，请稍候再试！');
        }
    }
    // 忘记密码2
    public function getForpwd2()
    {
        if(!is_null(session('member')))
        {
            // 如果上次的页面是登录页面，回首页
            if (strpos(url()->previous(),'/user/forpwd2')) {
                return redirect('/')->with('message','您已登录！');
            }
            else
            {
                return redirect(url()->previous())->with('message','您已登录！');
            }
        }
        $seo = ['title'=>'修改密码 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        return view($this->theme.'.user.forpwd2',compact('seo'));
    }
    // 忘记密码2
    public function postForpwd2(Request $res)
    {
        if(!is_null(session('member')))
        {
            return redirect(url()->previous())->with('message','您已登录！');
        }
        try {
            $code = $res->code;
            if (!session()->has('tmp_id') || is_null(session('tmp_id'))) {
                return redirect('/user/forpwd')->with('message','验证码已经失效，请重新发送！');
            }
            // 把code保存在密码字段里，省时省力~
            $old_pwd = User::where('id',session('tmp_id'))->value('password');
            if ($code != $old_pwd) {
                return back()->with('message','验证码错误！');
            }
            User::where('id',session('tmp_id'))->update(['password'=>encrypt($res->password),'last_ip'=>$res->ip(),'last_time'=>Carbon::now()]);
            $user = User::findOrFail(session('tmp_id'));
            // 登录
            session()->put('member',$user);
            session()->forget('tmp_id');
            return redirect(session('homeurl'))->with('message','修改密码成功！');
        } catch (\Throwable $e) {
            return back()->with('message','验证码发送失败，请稍候再试！');
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
