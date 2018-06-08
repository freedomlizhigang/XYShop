<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Common\Pay;
use App\Models\Good\Cart;
use App\Models\Good\Coupon;
use App\Models\Good\CouponUser;
use App\Models\Good\Fullgift;
use App\Models\Good\Order;
use App\Models\Good\Promotion;
use App\Models\User\Address;
use App\Models\User\Group;
use App\Models\User\SignConfig;
use App\Models\User\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  // 取所有购物车列表
  public function getCart()
  {
    try {
        $pos_id = 'cart';
        $title = '购物车';
        $list = Cart::with(['good'=>function($q){
                    $q->select('id','thumb','prom_type');
                }])->where('user_id',session('member')->id)->orderBy('updated_at','desc')->get();
        // 判断活动是不是已经结束了
        $promotion = Promotion::whereIn('id',$list->pluck('prom_id')->unique())->where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->get();
        $goodlists = [];
        $total_prices = 0;
        // 如果有购物车
        // 循环查商品，方便带出属性来
        foreach ($list as $k => $v) {
            $tmp_total_price = number_format($v->nums * $v->price,2,'.','');
            // 判断活动是不是已经结束了，结束以后重新计算折扣后的价格
            if ($v->prom_type != 0 && is_null($promotion->where('id',$v->prom_id)->first())) {
              // 算折扣
              try {
                  $points = User::where('id',$$v->user_id)->value('points');
                  $discount = Group::where('points','<=',$points)->orderBy('points','desc')->value('discount');
                  if (is_null($discount)) {
                      $discount = Group::orderBy('points','desc')->value('discount');
                  }
              } catch (\Throwable $e) {
                  $discount = 100;
              }
              $new_price = ($v->old_price * $discount) / 100;
              $tmp_total_price = number_format($v->nums * $new_price,2,'.','');
              Cart::where('id',$v->id)->update(['price'=>$new_price,'total_prices'=>$tmp_total_price,'prom_type'=>0,'prom_id'=>0]);
            }
            $goodlists[$k] = $v;
            $goodlists[$k]['total_prices'] = $tmp_total_price;
            $total_prices += $tmp_total_price;
        }
        // 总价
        $total_prices = number_format($total_prices,2,'.','');
        return view(cache('config')['theme'].'.cart',compact('pos_id','title','goodlists','total_prices'));
    } catch (\Throwable $e) {
        // dd($e);
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
      } catch (\Throwable $e) {
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
                  $q->select('id','thumb','prom_type');
              }])->whereIn('id',$cid)->where('user_id',session('member')->id)->orderBy('updated_at','desc')->get();
      $goodlists = [];
      $total_prices = 0;
      // 如果有购物车
      // 循环查商品，方便带出属性来
      foreach ($goods as $k => $v) {
          $tmp_total_price = number_format($v->nums * $v->price,2,'.','');
          $goodlists[$k] = $v;
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
      // 输出积分可选项
        $points = User::where('id',session('member')->id)->value('points');
        // 最多可用积分数，先算此单可用多少，看是不是比总积分多
        $pointconfig = SignConfig::findOrFail(1);
        $max_point = ($total_prices * $pointconfig->cash * $pointconfig->proportion)/100;
        $tmp_points = $max_point >= $points ? $points : $max_point;
        // 最大循环次数
        $max = floor($tmp_points/$pointconfig->block);
        // 积分可选项
        $point_select = [];
        for ($i=0; $i <= $max; $i++) {
            $point_select[] = $i*$pointconfig->block;
        }
      return view(cache('config')['theme'].'.createorder',compact('title','pos_id','goodlists','total_prices','default_address','address','coupon','count','cid_str','gift','point_select','points','pointconfig'));
    } catch (\Throwable $e) {
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
        // 没选择收货地址
        if ($order->address_id == 0 && $order->ziti == 0) {
            $url = url('editorder',$oid);
            return redirect($url)->with('message','请先选择收货地址！');
        }
        $info = (object)['pid'=>3];
        $paylist = Pay::where('status',1)->where('paystatus',1)->orderBy('id','asc')->get();
        $user = User::where('id',session('member')->id)->first();
        return view(cache('config')['theme'].'.pay',compact('info','order','paylist','title','pos_id','user'));
      } catch (\Throwable $e) {
        dd($e);
        return view('errors.404');
      }
  }
  // 提交订单
  public function getEditorder(Request $req)
  {
    try {
        $pos_id = 'cart';
        $title = '结算信息';
        // 找出购物车
        $oid = $req->oid;
        $order = Order::with(['good'=>function($q){
                    $q->with(['good'=>function($g){
                        $g->select('id','thumb','prom_type');
                    }]);
                }])->findOrFail($oid);
        $goodlists = [];
        $total_prices = 0;
        // 如果有购物车
        // 循环查商品，方便带出属性来
        foreach ($order->good as $k => $v) {
          $goodlists[$k] = $v;
          $tmp_total_price = number_format($v->nums * $v->price,2,'.','');
          $goodlists[$k]['total_prices'] = $tmp_total_price;
          $total_prices += $tmp_total_price;
        }
        // 计算总价
        $total_prices = number_format($total_prices,2,'.','');
        // 送货地址
        $address = Address::where('user_id',session('member')->id)->where('delflag',1)->orderBy('id','desc')->get();
        $default_address = $address->where('default',1)->first();
        return view(cache('config')['theme'].'.editorder',compact('title','pos_id','goodlists','total_prices','default_address','address','oid'));
    } catch (\Throwable $e) {
        dd($e);
        return view('errors.404');
    }
  }
}
