<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Common\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Common\Pay;
use App\Models\Good\Cart;
use App\Models\Good\Coupon;
use App\Models\Good\CouponUser;
use App\Models\Good\Fullgift;
use App\Models\Good\Order;
use App\Models\User\Address;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
  // 取所有购物车列表
  public function getCart()
  {
    try {
        $pos_id = 'cart';
        $title = '购物车';
        $list = Cart::with(['good'=>function($q){
                    $q->select('id','thumb');
                }])->where('user_id',session('member')->id)->orderBy('updated_at','desc')->get();
        $goodlists = [];
        $total_prices = 0;
        // 如果有购物车
        // 循环查商品，方便带出属性来
        foreach ($list as $k => $v) {
            $goodlists[$k] = $v;
            $tmp_total_price = number_format($v->nums * $v->price,2,'.','');
            $goodlists[$k]['total_prices'] = $tmp_total_price;
            $total_prices += $tmp_total_price;
        }
        // 总价
        $total_prices = number_format($total_prices,2,'.','');
        return view($this->theme.'.cart',compact('pos_id','title','goodlists','total_prices'));
    } catch (\Exception $e) {
        dd($e);
        return view('errors.404');
    }
  }
  // 提交结算页面
  public function postCreateorder(Request $req)
  {
      try {
          $cid = $req->cid;
          $time = time();
          $sname = 'order_info_'.$time;
          session()->put($sname,$cid);
          echo json_encode(['code'=>1,'msg'=>$time]);
          return;
      } catch (\Exception $e) {
          exit(json_encode(['code'=>0,'msg'=>$e->getMessage()]));
          return;
      }
  }
  // 提交订单
  public function getCreateorder(Request $req)
  {
    try {
      session()->forget('backurl');
      $pos_id = 'cart';
      $title = '结算信息';
      // 找出购物车
      $session_cid = trim(session('order_info_'.$req->rid),'.');
      if ($session_cid == '') {
        session()->forget('order_info_'.$req->rid);
        return back()->with('message','购物车是空的，先去购物吧！');
      }
      $cid = explode('.',$session_cid);
      $goods = Cart::with(['good'=>function($q){
                  $q->select('id','thumb');
              }])->whereIn('id',$cid)->where('user_id',session('member')->id)->orderBy('updated_at','desc')->get();
      $goodlists = [];
      $total_prices = 0;
      // 如果有购物车
      // 循环查商品，方便带出属性来
      foreach ($goods as $k => $v) {
          $goodlists[$k] = $v;
          $tmp_total_price = number_format($v->nums * $v->price,2,'.','');
          $goodlists[$k]['total_prices'] = $tmp_total_price;
          $total_prices += $tmp_total_price;
      }
      // 计算总价
      $total_prices = number_format($total_prices,2,'.','');
      $count = Cart::whereIn('id',$cid)->where('user_id',session('member')->id)->sum('nums');
      $cid_str = implode(',', $cid);
      // 送货地址
      $default_address = Address::where('user_id',session('member')->id)->where('delflag',1)->where('default',1)->orderBy('id','desc')->first();
      $address = Address::where('user_id',session('member')->id)->where('delflag',1)->orderBy('id','desc')->get();
      // 找出来可以用的优惠券
      $date = date('Y-m-d H:i:s');
      $cids = CouponUser::where('user_id',session('member')->id)->where('status',1)->where('endtime','>=',$date)->pluck('c_id');
      $coupon = Coupon::where('price','<=',$total_prices)->where('starttime','<=',$date)->where('endtime','>=',$date)->whereIn('id',$cids)->orderBy('sort','desc')->orderBy('id','desc')->where('delflag',1)->get();
      // 查有没有赠品
      $gift = Fullgift::with(['good'=>function($q){
                        $q->select('id','shop_price','title','thumb');
                    }])->where('price','<=',$total_prices)->where('status',1)->where('endtime','>=',date('Y-m-d H:i:s'))->where('store','>',0)->orderBy('price','desc')->first();
      return view($this->theme.'.createorder',compact('title','pos_id','goodlists','total_prices','default_address','address','coupon','count','cid_str','gift'));
    } catch (\Exception $e) {
      dd($e);
      return view('errors.404');
    }
  }
  // 提交订单完成，付款页面
  public function getPay(Request $req,$oid ='')
  {
      try {
        $pos_id = 'cart';
        $title = '选择支付方式';
        $order = Order::findOrFail($oid);
        $info = (object)['pid'=>3];
        $paylist = Pay::where('status',1)->where('paystatus',1)->orderBy('id','asc')->get();
        return view($this->theme.'.pay',compact('info','order','paylist','title','pos_id'));
      } catch (\Exception $e) {
        dd($e);
        return view('errors.404');
      }
  }
}
