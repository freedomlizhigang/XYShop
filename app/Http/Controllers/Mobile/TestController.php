<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Good\Order;
use App\Models\Good\Tuan;
use App\Models\User\User;
use DB;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function getLock(Request $req)
    {
        DB::beginTransaction();
        $backurl = session('backurl') == '' || session('backurl') == url('login') ? url('/') : session('backurl');
        try {
            $wechat = app('wechat.official_account');
            $oauth = $wechat->oauth;
            // 获取 OAuth 授权结果用户信息
            $wxuser = (object) ['id'=>'11111122212For','name'=>str_random(4),'sex'=>1,'avatar'=>'sd'];
            // 看这个用户在不在数据库，不在，添加并登录，在直接登录
            $user = User::where('openid',$wxuser->id)->sharedLock()->first();
            if (is_null($user)) {
                $sex = $wxuser->sex == '' ? 0 : $wxuser->sex;
                $res = User::create(['openid'=>$wxuser->id,'nickname'=>$wxuser->name,'sex'=>$sex,'thumb'=>$wxuser->avatar,'status'=>1,'last_ip'=>$req->ip(),'last_time'=>date('Y-m-d H:i:s')]);
                session()->put('member',(object)['id'=>$res->id,'openid'=>$res->openid]);
                // 弹出填写手机号功能
                session()->flash('nophone',1);
            }
            else
            {
                if ($user->status == 0) {
                    $message = '用户被禁用，请联系管理员！';
                    return view('errors.404',compact('message'));
                }
                User::where('openid',$wxuser->id)->update(['thumb'=>$wxuser->avatar,'last_ip'=>$req->ip(),'last_time'=>date('Y-m-d H:i:s')]);
                session()->put('member',(object)['id'=>$user->id,'openid'=>$user->openid]);
                if ($user->phone == '') {
                  // 弹出填写手机号功能
                  session()->flash('nophone',1);
                }
            }
            DB::commit();
            return 'success';
        } catch (\Throwable $e) {
            DB::rollback();
            dd($e);
        }

        // DB::beginTransaction();
        /*try {
            // User::where('id',1)->sharedLock()->increment('user_money',10);
            // User::where('id',1)->sharedLock()->decrement('points',10);
            // $user = User::where('id',1)->sharedLock()->first();
            // sleep(10);
            // DB::commit();
            // return $user;
        } catch (\Throwable $e) {
            // DB::rollback();
            dd($e);
        }*/
    }
    public function getLockget()
    {
        DB::beginTransaction();
        try {
            $user = User::where('id',1)->where('points','>',10)->sharedLock()->decrement('points',10);
            if ($this->dbtest()) {
                DB::commit();
            }
            else
            {
                DB::rollback();
            }
            return $user;
        } catch (\Throwable $e) {
            DB::rollback();
            dd($e);
        }
    }
    public function dbtest()
    {
        try {
            User::where('id',1)->update(['nickname'=>'李志刚']);
            return true;
        } catch (\Throwable $e) {
            return false;
        }

    }
}
