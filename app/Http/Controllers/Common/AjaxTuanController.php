<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxTuanController extends BaseController
{
    // 提交订单功能
    public function postCreateorder(Request $req)
    {
        try {
            $uid = $req->uid;
            if (!$uid) {
                $this->ajaxReturn('2',"请先登录！");
            }
            // 判断是否选择送货地址
            if ((!isset($req->aid) && !isset($req->ziti)) || ($req->aid == 0 && $req->ziti == 0)) {
                $this->ajaxReturn('0','请选择送货地址！');
            }
            // 找出所有 购物车
            $ids = explode(',', trim($req->cid,','));
            if (count($ids) == 0) {
                $this->ajaxReturn('0','购物车里是空的，请先购物！');
            }
            // 关掉一天以前的未付款订单，已经支付、发货的七天自动完成
            $this->closeOrder();
            // 所有产品总价
            $old_prices = Cart::whereIn('id',$ids)->sum('total_prices');
            $carts = Cart::whereIn('id',$ids)->orderBy('updated_at','desc')->get();
            // 在这里检查库存
            foreach ($carts as $v) {
                // 查看是不是活动中的商品，团购-限时-限量
                if($this->store($v->good_id,$v->good_spec_key,$v->nums) == false){
                    $this->ajaxReturn('0',$v->good_title.'，库存不足！');
                }
            }
            // 创建订单
            $order_id = app('com')->orderid();
            // 查出优惠券优惠多少
            $yh_price = 0;
            // 算折扣
            try {
                $points = $req->points;
                $discount = Group::where('points','<=',$points)->orderBy('points','desc')->value('discount');
                if (is_null($discount)) {
                    $discount = Group::orderBy('points','desc')->value('discount');
                }
            } catch (\Exception $e) {
                $discount = 100;
            }
            $prices = ($old_prices * $discount) / 100;
            // 优惠券
            $yhq_id = isset($req->yid) ? $req->yid : 0;
            if ($yhq_id) {
                $yh = CouponUser::where('id',$req->yid)->first();
                $yh_price = $yh->coupon->lessprice;
                $prices = $prices - $yh_price;
            }
            // 没有优惠券时查有没有赠品
            else
            {
                $mz = Fullgift::with(['good'=>function($q){
                        $q->select('id','shop_price','title');
                    }])->where('price','<=',$prices)->where('status',1)->where('endtime','>=',date('Y-m-d H:i:s'))->where('store','>',0)->orderBy('price','desc')->first();
            }
            $area = Address::where('id',$req->aid)->value('area');
            $order = ['order_id'=>$order_id,'user_id'=>$uid,'yhq_id'=>$yhq_id,'yh_price'=>$yh_price,'old_prices'=>$old_prices,'total_prices'=>$prices,'create_ip'=>$req->ip(),'address_id'=>$req->aid,'ziti'=>$req->ziti,'area'=>$area,'mark'=>$req->mark];
        } catch (\Exception $e) {
            // $this->ajaxReturn('0','添加失败，请稍后再试！');
            $this->ajaxReturn('0',$e->getMessage());
        }
        // 事务
        DB::beginTransaction();
        try {
            $order = Order::create($order);
            // 组合order_goods数组
            $order_goods = [];
            $clear_ids = [];
            $date = date('Y-m-d H:i:s');
            foreach ($carts as $k => $v) {
                $order_goods[$k] = ['user_id'=>$uid,'order_id'=>$order->id,'good_id'=>$v->good_id,'good_title'=>$v->good_title,'good_spec_key'=>$v->good_spec_key,'good_spec_name'=>$v->good_spec_name,'nums'=>$v->nums,'old_price'=>$v->old_price,'price'=>$v->price,'total_prices'=>$v->total_prices,'created_at'=>$date,'updated_at'=>$date,'prom_type'=>$v->prom_type,'prom_id'=>$v->prom_id];
                $clear_ids[] = $v->id;
            }
            // 如果有赠品，加上赠品
            if (isset($mz) && !is_null($mz) && !is_null($mz->good)) {
                $mz_good_spec = GoodSpecPrice::where('good_id',$mz->good_id)->orderBy('price','asc')->first();
                $good_spec_key = is_null($mz_good_spec) ? '' : $mz_good_spec->good_spec_key;
                $good_spec_name = is_null($mz_good_spec) ? '' : $mz_good_spec->good_spec_name;
                $price = is_null($mz_good_spec) ? $mz->good->price : $mz_good_spec->price;
                $order_goods[] = ['user_id'=>$uid,'order_id'=>$order->id,'good_id'=>$mz->good_id,'good_title'=>'赠品-'.$mz->good->title,'good_spec_key'=>$good_spec_key,'good_spec_name'=>$good_spec_name,'nums'=>1,'old_price'=>$price,'price'=>0,'total_prices'=>0,'created_at'=>$date,'updated_at'=>$date,'prom_type'=>3,'prom_id'=>$mz->id];
            }
            // 如果商品是空回滚
            if (count($order_goods) == 0) {
                // 出错回滚
                DB::rollBack();
                $this->ajaxReturn('0','请不要重复提交！');
            }
            // 插入
            OrderGood::insert($order_goods);
            // 清空购物车里的这几个产品
            Cart::whereIn('id',$clear_ids)->delete();
            // 下单减库存
            $this->updateStore($order->id);
            // 没出错，提交事务
            DB::commit();
            $this->ajaxReturn('1',$order->id);
        } catch (\Exception $e) {
            // 出错回滚
            DB::rollBack();
            Storage::disk('log')->prepend('updateOrder.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
            // $this->ajaxReturn('0','添加失败，请稍后再试！');
            $this->ajaxReturn('0',$e->getMessage());
        }
    }
}
