<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Common\AuthTmp;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use QrCode;
use Socialite;
use Storage;

class WxController extends Controller
{
    // 二维码登录
    public function getWxlogincode()
    {
        // 生成文件名
        $dir = public_path('upload/qrcode/');
        $path = $dir.'wxlogin.png';
        $src = '/upload/qrcode/wxlogin.png';
        // 存一个随机id到数据库里，以供后期确定是用户是否登录，session不可以，全局没办法判断
        $sid = uniqid().str_random(10);
        // 存，过期时间5分钟
        AuthTmp::create(['auth_id'=>$sid,'overtime'=>time() + 300]);
        $ewm = QrCode::format('png')->size(200)->generate(config('app.url').'/oauth/wxlogin?sid='.$sid,$path);
        $res = json_encode(['src'=>$src,'sid'=>$sid]);
        exit($res);
    }
    // 二维码注册
    public function getWxregcode()
    {
        // 生成文件名
        $dir = public_path('upload/qrcode/');
        $path = $dir.'wxreg.png';
        $src = '/upload/qrcode/wxreg.png';
        // 存一个随机id到数据库里，以供后期确定是用户是否登录，session不可以，全局没办法判断
        $sid = uniqid().str_random(10);
        // 存，过期时间5分钟
        AuthTmp::create(['auth_id'=>$sid,'overtime'=>time() + 300]);
        $ewm = QrCode::format('png')->size(200)->generate(config('app.url').'/oauth/wxreg?sid='.$sid,$path);
        $res = json_encode(['src'=>$src,'sid'=>$sid]);
        exit($res);
    }
    // 判断是否已经登录，PC版用到的，微信版直接就到了wx方法
    public function getWxscancode(Request $req)
    {
        $sid = $req->sid;
        $openid = AuthTmp::where('auth_id',$sid)->value('openid');
        if ($openid == '0' || is_null($openid)) {
            exit('0');
        }
        else
        {
            // 删除这个临时数据
            AuthTmp::where('auth_id',$sid)->delete();
            // 实现登录功能
            $user = User::where('status',1)->where('openid',$openid)->first();
            User::where('id',$user->id)->update(['last_ip'=>$req->ip(),'last_time'=>Carbon::now()]);
            session()->put('member',$user);
            // 更新购物车
            // $this->updateCart($user->id);
            echo $openid;
        }
    }
    // 微信端登陆功能
    public function getWxlogin_m()
    {
        $url = config('app.url').'/oauth/wxlogincallback_m';
        return Socialite::driver('wechat')->scopes(['snsapi_userinfo'])->setRedirectUrl($url)->redirect();
    }

    public function getLogincallback_m(Request $req)
    {
        $user = Socialite::driver('wechat')->user();
        // 如果是新用户，注册，老用户登陆
        if (is_null(User::where('openid',$user->id)->where('status',1)->first())) {
            User::create(['openid'=>$user->id,'nickname'=>$user->name,'thumb'=>$user->avatar,]);
        }
        else
        {
            User::where('openid',$user->id)->update(['thumb'=>$user->avatar,'nickname'=>$user->name]);
        }
        // Storage::disk('log')->prepend('oauth.log',json_encode($user).date('Y-m-d H:i:s'));
        // 实现登录功能
        $user = User::where('status',1)->where('openid',$user->id)->orderBy('id','asc')->first();
        User::where('id',$user->id)->update(['last_ip'=>$req->ip(),'last_time'=>Carbon::now()]);
        session()->put('member',$user);
        // 更新购物车
        // $this->updateCart($user->id);
        $url = session('homeurl') == config('app.url').'/user/login' || is_null(session('homeurl')) ? '/' : session('homeurl');
        return redirect($url);
    }
    // 微信注册功能
    public function getWxreg(Request $req)
    {
        $sid = $req->sid;
        // snsapi_base,snsapi_userinfo,?sid='.$sid
        $url = config('app.url').'/oauth/wxregcallback?sid='.$sid;
        return Socialite::driver('wechat')->scopes(['snsapi_userinfo'])->setRedirectUrl($url)->redirect();
    }
    public function getRegcallback(Request $req)
    {
        $sid = $req->sid;
        // 先清除5分钟前的临时数据
        AuthTmp::where('overtime','<',time())->delete();
        // 先判断是否已经失效
        if(is_null(AuthTmp::where('auth_id',$sid)->first()))
        {
            return redirect('/user/register')->with('message','二维码已失效，请刷新页面重试！');
        }
        // 正常，更新临时数据
        $user = Socialite::driver('wechat')->user();
        // 查有没有openid，没有注册
        AuthTmp::where('auth_id',$sid)->update(['openid'=>$user->id]);
        // dd('success');
        // 注册成新用户
        // 查一下这个用户存不存在
        if (is_null(User::where('openid',$user->id)->where('status',1)->first())) {
            User::create(['openid'=>$user->id,'nickname'=>$user->name,'thumb'=>$user->avatar]);
        }
        // Storage::disk('log')->prepend('oauth.log',json_encode($user).date('Y-m-d H:i:s'));
        // 更新购物车
        // $this->updateCart($user->id);
        $url = session('homeurl') == config('app.url').'/user/register' || is_null(session('homeurl')) ? '/' : session('homeurl');
        return redirect($url);
    }
    // PC微信登陆功能
    public function getWxlogin(Request $req)
    {
        $sid = $req->sid;
        // 如果是空的，说明直接登录的，生成sid
        if (is_null($sid)) {
            // 存一个随机id到数据库里，以供后期确定是用户是否登录，session不可以，全局没办法判断
            $sid = uniqid().str_random(10);
            // 存，过期时间5分钟
            AuthTmp::create(['auth_id'=>$sid,'overtime'=>time() + 300]);
        }
        // snsapi_base,snsapi_userinfo,?sid='.$sid
        $url = config('app.url').'/oauth/wxlogincallback?sid='.$sid;
        return Socialite::driver('wechat')->scopes(['snsapi_userinfo'])->setRedirectUrl($url)->redirect();
    }

    public function getLogincallback(Request $req)
    {
        $sid = $req->sid;
        // 先清除5分钟前的临时数据
        AuthTmp::where('overtime','<',time())->delete();
        // 先判断是否已经失效
        if(is_null(AuthTmp::where('auth_id',$sid)->first()))
        {
            return redirect('/user/login')->with('message','二维码已失效，请刷新页面重试！');
        }
        
        // 正常，更新临时数据
        $user = Socialite::driver('wechat')->user();
        // 查有没有openid，没有注册
        AuthTmp::where('auth_id',$sid)->update(['openid'=>$user->id]);
        if (is_null(User::where('openid',$user->id)->where('status',1)->orderBy('id','asc')->first())) {
            return redirect('/user/register')->with('message','没有此用户，请先注册！');
        }
        else
        {
            User::where('openid',$user->id)->update(['thumb'=>$user->avatar,'nickname'=>$user->name]);
        }
        // Storage::disk('log')->prepend('oauth.log',json_encode($user).date('Y-m-d H:i:s'));
        // 更新购物车
        // $this->updateCart($user->id);
        $url = session('homeurl') == config('app.url').'/user/login' || is_null(session('homeurl')) ? '/' : session('homeurl');
        return redirect($url);
    }

}