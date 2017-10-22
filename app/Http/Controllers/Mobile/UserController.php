<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Common\BaseController;
use App\Models\Common\Pay;
use App\Models\Good\Coupon;
use App\Models\Good\CouponUser;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\ReturnGood;
use App\Models\User\Consume;
use App\Models\User\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends BaseController
{
  // 用户中心
  public function getCenter()
  {
    $pos_id = 'center';
    $title = '用户中心';
    $info = User::where('id',session('member')->id)->first();
    return view($this->theme.'.user.center',compact('pos_id','title','info'));
  }
  // 订单列表
  public function getOrderlist($sid = '')
  {
    // $sid ''全部，1待付款，2待收货，3已完成，4已经关闭
    $pos_id = 'center';
    $title = '我的订单';
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
          $q->where('paystatus',1)->where('orderstatus',1);
          break;

        case '1':
          $q->where('paystatus',0)->where('orderstatus',1);
          break;

        default:
          break;
       }
    })->orderBy('id','desc')->paginate(20);
    return view($this->theme.'.user.order',compact('pos_id','title','list','sid'));
  }
  // 订单详细页面
  public function getOrderInfo($id = '')
  {
    try {
      $pos_id = 'center';
      $title = '订单详情';
      $order = Order::with('good')->findOrFail($id);
      return view($this->theme.'.user.orderinfo',compact('pos_id','title','order'));
    } catch (\Exception $e) {
      dd($e);
      return view('errors.404');
    }
  }
  // 申请退换
  public function getReturnGood($ogid = '')
  {
    $pos_id = 'center';
    $title = '申请退换';
    $ordergood = OrderGood::with('order')->findOrFail($ogid);
    // 判断是不是已经完成，三天内
    if ($ordergood->order->orderstatus !== 2 || strtotime($ordergood->order->confirm_at) <= time()-259200) {
      return back()->with('message','订单不在售后时间！');
    }
    if ($ordergood->shipstatus === 2 || $ordergood->shipstatus === 3) {
      return back()->with('message','已退货请不要重复提交！');
    }
    return view($this->theme.'.user.returngood',compact('pos_id','title','ordergood'));
  }
  public function postReturnGood(Request $req,$ogid = '')
  {
    try {
      $og = OrderGood::where('id',$ogid)->first();
      $data = ['user_id'=>$og->user_id,'order_id'=>$og->order_id,'good_id'=>$og->good_id,'good_title'=>$og->good_title,'good_spec_key'=>$og->good_spec_key,'good_spec_name'=>$og->good_spec_name,'nums'=>$og->nums,'price'=>$og->price,'total_prices'=>$og->total_prices,'mark'=>$req->mark];
      // OrderGood::where('id',$ogid)->update(['status'=>0]);
      ReturnGood::create($data);
      return redirect(url('user/orderinfo/'.$og->order_id))->with('message','退货申请已提交，请等待管理员联系您！');
    } catch (\Exception $e) {
      dd($e);
      return back()->with('message','提交失败，稍后再试！');
    }
  }
  // 登陆
  public function getLogin()
  {
    // 存下来源页面
    $backurl = url()->previous() == '' ? url('') : url()->previous();
    session()->put('backurl',$backurl);
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
        // 弹出填写手机号功能
        session()->flash('nophone',1);
      }
      else
      {
        User::where('openid',$wxuser->id)->update(['thumb'=>$wxuser->avatar,'last_ip'=>$req->ip(),'last_time'=>date('Y-m-d H:i:s')]);
        session()->put('member',(object)['id'=>$user->id,'openid'=>$user->openid]);
        if ($user->phone == '') {
          // 弹出填写手机号功能
          session()->flash('nophone',1);
        }
      }
      return redirect(session('backurl'));
    } catch (\Exception $e) {
      return redirect(session('backurl'));
    }
  }
  // 修改个人信息
  public function getUserinfo()
  {
    $pos_id = 'center';
    $title = '修改个人信息';
    $info = User::findOrFail(session('member')->id);
    return view($this->theme.'.user.userinfo',compact('pos_id','title','info'));
  }
  public function postUserinfo(Request $req)
  {
    $validator = Validator::make($req->input(), [
      'data.nickname' => 'required|max:255',
      'data.phone' => 'required|digits:11',
      'data.email' => 'required|email',
    ]);
    $attrs = array(
      'data.nickname' => '昵称',
      'data.phone' => '手机号',
      'data.email' => '邮箱',
    );
    $validator->setAttributeNames($attrs);
    if ($validator->fails()) {
        // 如果有错误，提示第一条
        return back()->with('message',$validator->errors()->all()[0]);
    }
    $data = $req->input('data');
    User::where('id',session('member')->id)->update($data);
    return redirect(url('center'))->with('message','修改个人信息成功！');
  }
  // 修改密码
  public function getPasswd()
  {
    $pos_id = 'center';
    $title = '修改密码';
    return view($this->theme.'.user.passwd',compact('pos_id','title'));
  }
  public function postPasswd(Request $req)
  {
    $validator = Validator::make($req->input(), [
      'passwd' => 'required|min:6|max:15|confirmed',
      'passwd_confirmation' => 'required|min:6|max:15',
    ]);
    $attrs = array(
      'passwd' => '新密码',
      'passwd_confirmation' => '新密码',
    );
    $validator->setAttributeNames($attrs);
    if ($validator->fails()) {
        // 如果有错误，提示第一条
        return back()->with('message',$validator->errors()->all()[0]);
    }
    User::where('id',session('member')->id)->update(['password'=>encrypt($req->passwd)]);
    return redirect(url('center'))->with('message','修改密码成功！');
  }
  // 消费记录
  public function getConsume()
  {
    $pos_id = 'center';
    $title = '消费记录';
    $consume = Consume::where('user_id',session('member')->id)->orderBy('id','desc')->paginate(20);
    return view($this->theme.'.user.consume',compact('pos_id','title','consume'));
  }
  // 优惠券
  public function getCoupon()
  {
    $pos_id = 'center';
    $title = '消费记录';
    $list = CouponUser::with('coupon')->where('user_id',session('member')->id)->orderBy('id','desc')->paginate(20);
    return view($this->theme.'.user.coupon',compact('pos_id','title','list'));
  }
}
