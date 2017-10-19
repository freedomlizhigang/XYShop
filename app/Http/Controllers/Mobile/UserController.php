<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\Order;
use App\Models\User\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
  // 用户中心
  public function getCenter()
  {
    $pos_id = 'center';
    $seo = (object) ['title'=>'用户中心-'.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
    $info = User::where('id',session('member')->id)->first();
    return view($this->theme.'.user.center',compact('pos_id','seo','info'));
  }
  // 订单列表
  public function getOrderlist($sid = '')
  {
    // $sid ''全部，1待付款，2待收货，3已完成，4已经关闭
    $pos_id = 'center';
    $seo = (object) ['title'=>'用户中心-'.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
    $list = Order::with(['good'=>function($q){
              $q->select('id','good_id','order_id','good_title')->with(['good'=>function($r){
                $r->select('id','thumb');
              }]);
            }])->where('user_id',session('member')->id)->where('status',1)->where(function($q) use($sid){
      switch ($sid) {
        case '4':
          $q->where('orderstatus',0);
          break;

        case '3':
          $q->where('orderstatus',2);
          break;

        case '2':
          $q->where('paystatus',1)->where('shipstatus',1)->where('orderstatus',1);
          break;

        case '1':
          $q->where('paystatus',0);
          break;

        default:
          break;
       }
    })->orderBy('id','desc')->paginate(20);
    return view($this->theme.'.user.order',compact('pos_id','seo','list','sid'));
  }
  // 登陆
  public function getLogin()
  {
    // 存下来源页面
    session()->put('backurl',url()->previous());
    $wechat = app('wechat');
    $oauth = $wechat->oauth->withRedirectUrl(config('app.url').'/wxlogin');
    return $oauth->redirect();
    /*$pos_id = 'center';
    $seo = (object) ['title'=>'用户登陆-'.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
    return view($this->theme.'.user.login',compact('pos_id','seo'));*/
  }
  // 微信直接登陆
  public function getWxLogin(Request $req)
  {
    try {
      $wechat = app('wechat');
      $oauth = $wechat->oauth;
      // 获取 OAuth 授权结果用户信息
      $wxuser = $oauth->user();
      // 看这个用户在不在数据库，不在，添加并登录，在直接登录
      $user = User::where('openid',$wxuser->id)->where('status',1)->first();
      if (is_null($user)) {
        $res = User::create(['openid'=>$wxuser->id,'nickname'=>$wxuser->name,'sex'=>$wxuser->sex,'thumb'=>$wxuser->avatar,'status'=>1,'last_ip'=>$req->ip(),'last_time'=>date('Y-m-d H:i:s')]);
        session()->put('member',(object)['id'=>$res->id,'openid'=>$res->openid]);
      }
      else
      {
        User::where('openid',$wxuser->id)->update(['thumb'=>$wxuser->avatar,'last_ip'=>$req->ip(),'last_time'=>date('Y-m-d H:i:s')]);
        session()->put('member',(object)['id'=>$user->id,'openid'=>$user->openid]);
      }
      return redirect(session('backurl'));
    } catch (\Exception $e) {
      return redirect(session('backurl'));
    }
  }
}
