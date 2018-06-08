<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Common\OrderApi;
use App\Http\Controllers\Controller;
use App\Models\Good\Good;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\User\Address;
use App\Models\User\User;
use DB;
use Illuminate\Http\Request;
use Storage;

class OrderController extends Controller
{
    public function index(Request $req)
    {
        $title = '订单列表';
        $q = $req->input('q');
        $key = $req->input('key');
        $prom_type = $req->input('prom_type');
        $starttime = $req->input('starttime');
        $endtime = $req->input('endtime');
        $status = $req->input('status',1);
        $shipstatus = $req->input('shipstatus','');
        $paystatus = $req->input('paystatus',1);
        // 找出订单
        $orders = Order::with(['good'=>function($q){
                    $q->select('id','user_id','order_id','good_id','good_title','good_spec_key','good_spec_name','nums','price','total_prices')->with('good');
                },'address','user'])->where(function($r) use($q){
                    if ($q != '') {
                        // 查出来收货人ID
                        $uid = Address::where('people','like',"%$q%")->orWhere('phone','like',"%$q%")->pluck('id')->toArray();
                        $uid_2 = User::where('nickname','like',"%$q%")->orWhere('phone','like',"%$q%")->pluck('id')->toArray();
                        $r->whereIn('address_id',$uid)->orWhere(function($ss) use($uid_2){
                            $ss->whereIn('user_id',$uid_2);
                        });
                    }
                })->where(function($r) use($key){
                    if ($key != '') {
                        // 查出来订单ID
                        $oids = OrderGood::where('good_title','like',"%$key%")->pluck('order_id')->toArray();
                        $r->whereIn('id',$oids)->orWhere('order_id','like',"%$key%");
                    }
                })->where(function($q) use($starttime){
                    if ($starttime != '') {
                        $q->where('created_at','>',$starttime);
                    }
                })->where(function($q) use($endtime){
                    if ($endtime != '') {
                        $q->where('created_at','<',$endtime);
                    }
                })->where(function($q) use($status){
                    if ($status != '') {
                        $q->where('orderstatus',$status);
                    }
                })->where(function($q) use($shipstatus){
                    if ($shipstatus != '') {
                        $q->where('shipstatus',$shipstatus);
                    }
                })->where(function($q) use($paystatus){
                    if ($paystatus != '') {
                        $q->where('paystatus',$paystatus);
                    }
                })->where(function($q) use($prom_type){
                    if ($prom_type != '') {
                        $q->where('prom_type',$prom_type);
                    }
                })->where('display',1)->where('status',1)->orderBy('id','desc')->paginate(10);
        return view('admin.order.index',compact('title','orders','q','status','starttime','endtime','paystatus','shipstatus','key','prom_type'));
    }
    // 打印
    public function getPrint($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.print',compact('order'));
    }
    // 批量发货
    public function postAllShip(Request $req)
    {
        $sids = $req->sids;
        // 找出这里边所有已经付款的
        $sids = Order::whereIn('id',$sids)->where('paystatus',1)->pluck('id');
        Order::whereIn('id',$sids)->update(['shipstatus'=>1,'ship_at'=>date('Y-m-d H:i:s')]);
        OrderGood::whereIn('order_id',$sids)->update(['shipstatus'=>1]);
        return back()->with('message','发货成功！');
    }
    // 批量关闭
    public function postAllDel(Request $req)
    {
        DB::beginTransaction();
        try {
            $sids = $req->sids;
            // 查有没有付款，付款了退到余额
            $order = Order::whereIn('id',$sids)->select('id','user_id','order_id','paystatus','total_prices','orderstatus','points')->get();
            foreach ($order as $k => $v) {
                if ($v->paystatus && $v->orderstatus == 1) {
                    User::where('id',$v->user_id)->increment('user_money',$v->total_prices);
                    // 消费记录
                    app('com')->consume($v->user_id,$v->order_id,$v->total_prices,'取消订单返现('.$v->order_id.')',1);
                }
                // 增加库存
                OrderApi::updateStore($v->id,1);
                // 返还积分
                User::where('id',$v->user_id)->increment('points',$v->points);
            }
            Order::whereIn('id',$sids)->update(['orderstatus'=>0]);
            // 没出错，提交事务
            DB::commit();
            return back()->with('message','关闭成功！');
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            dd($e);
            return back()->with('message','关闭失败，请稍后再试！');
        }
        $sids = $req->sids;
        return back()->with('message','关闭成功！');
    }
    // 关闭
    public function getDel($id = '')
    {
        DB::beginTransaction();
        try {
            // 查有没有付款，付款了退到余额
            $order = Order::where('id',$id)->select('id','user_id','order_id','paystatus','total_prices','orderstatus')->first();
            if ($order->paystatus && $order->orderstatus == 1) {
                User::where('id',$order->user_id)->increment('user_money',$order->total_prices);
                // 消费记录
                app('com')->consume($order->user_id,$order->order_id,$order->total_prices,'取消订单返现('.$order->order_id.')',1);
            }
            // 增加库存
            OrderApi::updateStore($order->id,1);
            // 返还积分
            User::where('id',$order->user_id)->increment('points',$order->points);
            Order::where('id',$id)->update(['orderstatus'=>0]);
            // 没出错，提交事务
            DB::commit();
            return back()->with('message','关闭成功！');
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            return back()->with('message','关闭失败，请稍后再试！');
        }
    }
    // 发货
    public function getShip($id = '')
    {
        $title = '快递单号';
        return view('admin.order.ship',compact('title','id'));
    }
    public function postShip(Request $req,$id = '')
    {
        // 更新为已经发货
        if (Order::where('id',$id)->value('paystatus') == 1) {
            Order::where('id',$id)->update(['shipstatus'=>1,'shopmark'=>$req->input('data.shopmark'),'ship_at'=>date('Y-m-d H:i:s')]);
            OrderGood::where('order_id',$id)->update(['shipstatus'=>1]);
            return $this->adminJson(1,'发货成功！');
        }
        return $this->adminJson(0,'还未付款！');
    }
    // 自提、完成
    public function getZiti($id = '')
    {
        Order::where('id',$id)->update(['orderstatus'=>2]);
        return back()->with('message','自提成功！');
    }
    // 批量自提
    public function postAllZiti(Request $req)
    {
        $sids = $req->sids;
        $sids = Order::whereIn('id',$sids)->where('paystatus',1)->pluck('id');
        Order::whereIn('id',$sids)->update(['orderstatus'=>2]);
        return back()->with('message','设置自提成功！');
    }
}