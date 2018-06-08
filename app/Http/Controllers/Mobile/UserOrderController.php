<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\ReturnGood;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    // 订单列表
    public function getOrderlist($sid = '')
    {
        // $sid ''全部，1待付款，2待收货，3已完成，4已经关闭
        $pos_id = 'center';
        $title = '我的订单';
        $list = Order::with(['good'=>function($q){
                  $q->select('id','good_id','order_id','good_title')->with(['good'=>function($r){
                    $r->select('id','thumb','prom_type');
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
        return view(cache('config')['theme'].'.user.order',compact('pos_id','title','list','sid'));
    }
    // 订单详细页面
    public function getOrderInfo($id = '')
    {
        try {
          $pos_id = 'center';
          $title = '订单详情';
          $order = Order::with('good')->findOrFail($id);
          return view(cache('config')['theme'].'.user.orderinfo',compact('pos_id','title','order'));
        } catch (\Throwable $e) {
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
        return view(cache('config')['theme'].'.user.returngood',compact('pos_id','title','ordergood'));
    }
    public function postReturnGood(Request $req,$ogid = '')
    {
        try {
          $og = OrderGood::where('id',$ogid)->first();
          $data = ['user_id'=>$og->user_id,'order_id'=>$og->order_id,'good_id'=>$og->good_id,'good_title'=>$og->good_title,'good_spec_key'=>$og->good_spec_key,'good_spec_name'=>$og->good_spec_name,'nums'=>$og->nums,'price'=>$og->price,'total_prices'=>$og->total_prices,'mark'=>$req->mark];
          ReturnGood::create($data);
          return redirect(url('user/orderinfo/'.$og->order_id))->with('message','退货申请已提交，请等待管理员联系您！');
        } catch (\Throwable $e) {
          dd($e);
          return back()->with('message','提交失败，稍后再试！');
        }
    }
}
