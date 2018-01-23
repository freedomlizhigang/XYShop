<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Good\Fullgift;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\Timetobuy;
use App\Models\Good\Tuan;
use App\Models\Good\TuanUser;
use App\Models\User\User;
use DB;
use Illuminate\Http\Request;
use Storage;

class BaseController extends Controller
{
    // 检查库存
    protected function store($id,$spec_key,$num)
    {
        if ($spec_key == '') {
            $store = Good::where('id',$id)->where('status',1)->value('store');
        }
        else
        {
            $store = GoodSpecPrice::where('good_id',$id)->where('item_id',$spec_key)->value('store');
        }
        $store = is_null($store) ? 0 : $store;
        if ($store < $num) {
            return false;
        }
        return true;
    }
    // 更新库存，活动内容同时更新
    protected function updateStore($oid = '',$type = 0)
    {
        // 加库存，先找出来所有的商品ID与商品属性ID
        $goods = OrderGood::where('order_id',$oid)->where('status',1)->select('id','good_id','good_spec_key','nums','prom_id','prom_type','user_id')->get();
        if ($type) {
            // 循环，判断是直接减商品库存，还是减带属性的库存
            foreach ($goods as $k => $v) {
                if ($v->good_spec_key != '') {
                    GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->spec_key)->sharedLock()->increment('store',$v->nums);
                }
                Good::where('id',$v->good_id)->sharedLock()->increment('store',$v->nums); 
                // 加销量
                Good::where('id',$v->good_id)->sharedLock()->decrement('sales',$v->nums);
                // 查看活动情况，参加人加一，数量减一
                if ($v->prom_type === 1) {
                    Timetobuy::where('id',$v->prom_id)->sharedLock()->increment('good_num',$v->nums);
                    Timetobuy::where('id',$v->prom_id)->sharedLock()->decrement('buy_num');
                    Timetobuy::where('id',$v->prom_id)->sharedLock()->decrement('order_num',$v->nums);
                }
                if ($v->prom_type === 2) {
                    TuanUser::create(['status'=>1,'t_id'=>$v->prom_id,'user_id'=>$v->user_id]);
                    Tuan::where('id',$v->prom_id)->sharedLock()->increment('store',$v->nums);
                    Tuan::where('id',$v->prom_id)->sharedLock()->decrement('buy_num',$v->nums);
                }
                if ($v->prom_type === 3) {
                    Fullgift::where('id',$v->prom_id)->sharedLock()->increment('store',$v->nums);
                }
            }
        }
        else
        {
            // 循环，判断是直接减商品库存，还是减带属性的库存
            foreach ($goods as $k => $v) {
                if ($v->good_spec_key != '') {
                    GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->spec_key)->sharedLock()->decrement('store',$v->nums);
                }
                Good::where('id',$v->good_id)->sharedLock()->decrement('store',$v->nums); 
                // 加销量
                Good::where('id',$v->good_id)->sharedLock()->increment('sales',$v->nums);
                // 查看活动情况，参加人加一，数量减一
                if ($v->prom_type === 1) {
                    Timetobuy::where('id',$v->prom_id)->sharedLock()->decrement('good_num',$v->nums);
                    Timetobuy::where('id',$v->prom_id)->sharedLock()->increment('buy_num');
                    Timetobuy::where('id',$v->prom_id)->sharedLock()->increment('order_num');
                }
                if ($v->prom_type === 2) {
                    TuanUser::where('t_id',$v->prom_id)->where('user_id',$v->user_id)->sharedLock()->update(['status'=>0]);
                    Tuan::where('id',$v->prom_id)->sharedLock()->decrement('store',$v->nums);
                    Tuan::where('id',$v->prom_id)->sharedLock()->increment('buy_num',$v->nums);
                }
                if ($v->prom_type === 3) {
                    Fullgift::where('id',$v->prom_id)->sharedLock()->decrement('store',$v->nums);
                }
            }
        }
    }
    // 关单，有条件就做成定时任务，这里是个大坑
    protected function closeOrder()
    {
        DB::beginTransaction();
        try {
            $order_list = Order::where('orderstatus',1)->where('paystatus',0)->where('created_at','<',Carbon::now()->subday())->pluck('id');
            foreach ($order_list as $o) {
                // 增加库存
                $this->updateStore($o,1);
            }
            // 关掉一天以前的未付款订单，同时把团购或者其它活动里的数量增加回去
            Order::where('orderstatus',1)->where('paystatus',0)->where('created_at','<',Carbon::now()->subday())->sharedLock()->update(['orderstatus'=>0]);
            // 已经支付、发货的七天自动完成
            Order::where('orderstatus',1)->where('shipstatus',1)->where('paystatus',1)->where('ship_at','<',Carbon::now()->subday(7))->sharedLock()->update(['orderstatus'=>2,'confirm_at'=>date('Y-m-d H:i:s')]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Storage::disk('log')->prepend('updateOrder.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
            $this->ajaxReturn('0','提交失败，请稍后再试！');
        }
    }
    // 支付完成操作
    public function updateOrder($order = '',$paymod = '余额')
    {
        // 事务
        DB::beginTransaction();
        try {
            Order::where('id',$order->id)->sharedLock()->update(['paystatus'=>1,'pay_name'=>$paymod]);
            User::where('id',$order->user_id)->sharedLock()->increment('points',$order->total_prices);
            // 消费记录
            app('com')->consume($order->user_id,$order->id,$order->total_prices,$paymod.'支付订单（'.$order->order_id.'）');
            // 没出错，提交事务
            DB::commit();
            return true;
        } catch (\Exception $e) {
            // 出错回滚
            DB::rollBack();
            // dd($e->getMessage());
            Storage::disk('log')->prepend('updateOrder.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
            return false;
        }
    }
    // ajax返回
    public function ajaxReturn($code = '1',$msg = '')
    {
        exit(json_encode(['code'=>$code,'msg'=>$msg]));
        return;
    }
}
