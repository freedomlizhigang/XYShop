<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class UserController extends BaseController
{
    // 登录
	public function postLogin(Request $req)
	{
		$validator = Validator::make($req->input(), [
	        'username' => 'required',
	        'password' => 'required',
	    ]);
	     $attrs = array(
            'username' => '用户名',
            'password' => '密码',
        );
        $validator->setAttributeNames($attrs);
        if ($validator->fails()) {
            // 如果有错误，提示第一条
            $result = $this->resJson(0,$validator->errors()->all()[0],'');
            return $result;
        }
        // 查出用户的数据
        $username = trim($req->username);
        $password = $req->password;
        $user = User::where('username',$username)->where('status',1)->first();
        if (is_null($user)) {
        	return  $this->resJson(0,'用户不存在或已被禁用！','');
	    }
	    else
	    {
		    if ($password != decrypt($user->password)) {
		    	return  $this->resJson(0,'密码不正确！','');
		    }
		    try {
			    // 生成新的token，ID+密钥+当前时间+3600*24*365
			    $token = encrypt($user->id.'.'.config('jwt.jwt-key').'.'.config('jwt.addtime'));
	            User::where('id',$user->id)->update(['last_ip'=>$req->ip(),'last_time'=>Carbon::now()->toDateTimeString(),'token'=>$token]);
		    	// 返回当前user信息
		    	$user = User::findOrFail($user->id);
		    	return $this->resJson(1,'登录成功！',$user);
		    } catch (\Exception $e) {
		    	return $this->resJson(0,$e->getMessage());
		    }
	    }
	}
	// 注册
    public function postRegister(Request $req)
    {
    	$validator = Validator::make($req->input(), [
	        'username' => 'required|between:2,20',
	        'password' => 'required|between:6,20',
	        'email' => 'required|email',
	    ]);
	     $attrs = array(
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮箱',
        );
        $validator->setAttributeNames($attrs);
        if ($validator->fails()) {
            // 如果有错误，提示第一条
            $result = $this->resJson(0,$validator->errors()->all()[0],'');
            return $result;
        }

    	if(!is_null(session('member')))
		{
			return redirect(url()->previous())->with('message','您已登录！');
		}

		$username = trim($req->username);
    	// 查一样有没有重复的用户名
    	$ishav = User::where('username',$username)->first();
    	if (!is_null($ishav)) {
    		return $this->resJson(0,'用户名已经被使用，请换一个再试！');
    	}
    	$pwd = encrypt($req->passwords);
    	$email = $req->email;
    	try {
	    	$user = User::create(['username'=>$username,'password'=>$pwd,'email'=>$email,'last_ip'=>$req->ip(),'last_time'=>Carbon::now()->toDateTimeString()]);
	    	// 生成token并更新
	    	$token = encrypt($user->id.'.'.config('jwt.jwt-key').'.'.config('jwt.addtime'));
	    	User::where('id',$user->id)->update(['token'=>$token]);
	    	$user->token = $token;
	    	return $this->resJson(1,'注册成功!',$user);
    	} catch (\Exception $e) {
    		return $this->resJson(0,$e->getMessage());
    	}
    }
    // 退出登录
    public function postLogout(Request $req)
    {
    	$validator = Validator::make($req->input(), [
	        'token' => 'required',
	        'uid' => 'required|integer',
	    ]);
	     $attrs = array(
            'token' => 'Token',
            'uid' => '用户ID',
        );
        $validator->setAttributeNames($attrs);
        if ($validator->fails()) {
            // 如果有错误，提示第一条
            $result = $this->resJson(0,$validator->errors()->all()[0],'');
            return $result;
        }
    	// 清除token
    	User::where('id',$req->uid)->update(['token'=>'']);
    	return $this->resJson(1,'退出成功！');
    }
}
