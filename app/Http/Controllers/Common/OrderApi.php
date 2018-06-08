<?php

namespace App\Http\Controllers\Common;

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

class OrderApi
{
    // 检查库存
    public static function store($id,$spec_key,$num)
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
    // 更新库存，活动内容同时更新-把自增改为（查询-设置）
    public static function updateStore($oid = '',$type = 0)
    {
        // 加库存，先找出来所有的商品ID与商品属性ID
        $goods = OrderGood::where('order_id',$oid)->where('status',1)->select('id','good_id','good_spec_key','nums','prom_id','prom_type','user_id')->get();
        // 增-退订
        if ($type) {
            // Log::info('增-退订'.$goods->toJson());
            // 循环，判断是直接减商品库存，还是减带属性的库存
            foreach ($goods as $k => $v) {
                $nums = $v->nums;
                $good = Good::where('id',$v->good_id)->sharedLock()->first();
                Good::where('id',$v->good_id)->update(['store'=>$good->store + $nums,'sales'=>$good->sales - $nums]);
                if ($v->good_spec_key != '') {
                    $store = GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->good_spec_key)->sharedLock()->value('store');
                    GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->good_spec_key)->update(['store'=>$store + $nums]);
                }
                switch ($v->prom_type) {
                    // 赠品
                    case 3:
                        $fullgift = Fullgift::where('id',$v->prom_id)->sharedLock()->value('store');
                        Fullgift::where('id',$v->prom_id)->update(['store'=>$fullgift + $nums]);
                        break;
                    // 团购
                    case 2:
                        TuanUser::where('t_id',$v->prom_id)->where('user_id',$v->user_id)->update(['status'=>0]);
                        $tuan = Tuan::where('id',$v->prom_id)->sharedLock()->first();
                        Tuan::where('id',$v->prom_id)->update(['store'=>$tuan->store + $nums,'buy_num'=>$tuan->buy_num - $nums]);
                        break;
                    // 查看抢购，参加人加一，数量减一
                    case 1:
                        $ttb = Timetobuy::where('id',$v->prom_id)->sharedLock()->first();
                        Timetobuy::where('id',$v->prom_id)->update(['good_num'=>$ttb->good_num + $nums,'buy_num'=>$ttb->buy_num - 1,'order_num'=>$ttb->order_num - 1]);
                        break;
                    default:
                        break;
                }
            }
        }
        else
        {
            // Log::info('减库存加销量'.$goods->toJson());
            // 减
            // 循环，判断是直接减商品库存，还是减带属性的库存
            foreach ($goods as $k => $v) {
                $nums = $v->nums;
                $good = Good::where('id',$v->good_id)->sharedLock()->first();
                // 减库存加销量
                if ($good->store >= $nums) {
                    Good::where('id',$v->good_id)->update(['store'=>$good->store - $nums,'sales'=>$good->sales + $nums]);
                    if ($v->good_spec_key != '') {
                        $store = GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->good_spec_key)->sharedLock()->value('store');
                        GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->good_spec_key)->update(['store'=>$store - $nums]);
                    }
                }
                else
                {
                    throw new \Exception("库存不足！", 1);
                }
                switch ($v->prom_type) {
                    // 赠品
                    case 3:
                        $fullgift = Fullgift::where('id',$v->prom_id)->sharedLock()->value('store');
                        if ($fullgift < $nums) {
                            throw new \Exception("赠品库存不足！", 1);
                        }
                        Fullgift::where('id',$v->prom_id)->update(['store'=>$fullgift - $nums]);
                        break;
                    // 团购
                    case 2:
                        TuanUser::create(['status'=>1,'t_id'=>$v->prom_id,'user_id'=>$v->user_id]);
                        $tuan = Tuan::where('id',$v->prom_id)->sharedLock()->first();
                        if ($tuan->store < $nums) {
                            throw new \Exception("库存不足！", 1);
                        }
                        Tuan::where('id',$v->prom_id)->update(['store'=>$tuan->store - $nums,'buy_num'=>$tuan->buy_num + $nums]);
                        break;
                    // 查看抢购，参加人加一，数量减一
                    case 1:
                        $ttb = Timetobuy::where('id',$v->prom_id)->sharedLock()->first();
                        if ($ttb->good_num < $nums) {
                            throw new \Exception("库存不足！", 1);
                        }
                        Timetobuy::where('id',$v->prom_id)->update(['good_num'=>$ttb->good_num - $nums,'buy_num'=>$ttb->buy_num + 1,'order_num'=>$ttb->order_num + 1]);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    // 支付完成操作
    public static function updateOrder($order = '',$paymod = '余额')
    {
        try {
            Order::where('id',$order->id)->sharedLock()->update(['paystatus'=>1,'pay_name'=>$paymod,'paytime'=>date('Y-m-d H:i:s')]);
            // 如果是团购，执行团购完成的操作
            if ($order->prom_type == '2') {
                if (!$this->updateTuan($order)) {
                    return false;
                }
            }
            // 消费记录
            app('com')->consume($order->user_id,$order->id,$order->total_prices,$paymod.'支付订单（'.$order->order_id.'）');
            // 没出错
            return true;
        } catch (\Throwable $e) {
            // 出错回滚
            Log::warning('支付完成操作失败：'.$e->getLine().' - '.$e->getMessage());
            return false;
        }
    }
    private function updateTuan($order = '')
    {
        try {
            /*
            1. 按团购id找上一个支付完成的订单，得到团购订单号a
            2. 看a是否存在（满员），存在不满员->更新成一致；不存在||满员->生成新的更新
             */
            $old_t_orderid = Order::where('tuan_id',$order->tuan_id)->where('paystatus',1)->where('orderstatus',1)->where('prom_type',2)->orderBy('id','desc')->value('t_orderid');
            $nums = $old_t_orderid == '' || is_null($old_t_orderid) ? 0 : Order::where('tuan_id',$order->tuan_id)->where('t_orderid',$old_t_orderid)->where('paystatus',1)->where('orderstatus',1)->where('prom_type',2)->count();
            $tuan_num = Tuan::where('id',$order->tuan_id)->value('tuan_num');
            $t_orderid = md5(uniqid().str_random(20));
            // 判断是新开团还是参团
            if ($nums+1 <= $tuan_num) {
                Order::where('id',$order->id)->update(['display'=>1,'t_orderid'=>$old_t_orderid]);
            }
            else
            {
                Order::where('id',$order->id)->update(['t_orderid'=>$t_orderid]);
            }
            // 满员显示订单
            if ($nums+1 == $tuan_num)
            {
                Order::where('t_orderid',$old_t_orderid)->where('prom_type',2)->where('orderstatus',1)->where('paystatus',1)->update(['display'=>1]);
            }
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
