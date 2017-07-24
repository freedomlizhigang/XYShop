<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\Tuan;
use App\Models\Good\TuanUser;
use App\Models\User\Address;
use DB;
use Illuminate\Http\Request;

class AjaxTuanController extends BaseController
{
    // 添加购物车
    public function postAddTuan(Request $req)
    {
        // 查看有没有在购物车里，有累计数量
        DB::beginTransaction();
        try {
            // 清除完成
            $id = $req->gid;
            $tid = $req->tid;
            $spec_key = $req->spec_key;
            $num = $req->num;
            $price = $req->gp;
            // 判断是否选择送货地址
            if (!isset($req->aid) || !isset($req->ziti)) {
                $this->ajaxReturn('0','请选择送货地址！');
            }
            // 检查库存
            if(!$this->store($id,$spec_key,$num))
            {
            	$this->ajaxReturn('0','库存不足！');
            }
            $userid = $req->uid;
            // 先检查是否参加过
            if(!is_null(TuanUser::where('user_id',$userid)->where('t_id',$tid)->first()))
            {
                $this->ajaxReturn('0','参加过，请不要重复参加！');
            }
            // 没参过团的
            else
            {
                // 人数加1，库存减1
                Tuan::where('id',$tid)->increment('havnums');
                Tuan::where('id',$tid)->decrement('store');
                TuanUser::create(['user_id'=>$userid,'t_id'=>$tid]);
            }
            $nums = $num;
            $total_prices = $price * $nums;
            $area = Address::where('id',$req->aid)->value('area');
            // 创建订单，原价
            if (!is_null($spec_key)) {
                $old_prices = GoodSpecPrice::where('good_id',$id)->where('key',$spec_key)->value('price');
            }
            else
            {
                $old_prices = Good::where('id',$id)->value('price');
            }
            $order_id = app('com')->orderid();
            $order = ['order_id'=>$order_id,'tuan_id'=>$tid,'user_id'=>$userid,'yhq_id'=>'0','yh_price'=>0,'old_prices'=>$old_prices,'total_prices'=>$total_prices,'create_ip'=>$req->ip(),'address_id'=>$req->aid,'ziti'=>$req->ziti,'area'=>$area];
            // 真正的创建
            $order = Order::create($order);
            $spec_key_name = GoodSpecPrice::where('good_id',$id)->where('key',$spec_key)->value('key_name');
            $good_title = Good::where('id',$id)->value('title');
            // 组合order_goods数组
            $order_goods = ['user_id'=>$userid,'order_id'=>$order->id,'good_id'=>$id,'good_title'=>$good_title,'good_spec_key'=>$spec_key,'good_spec_name'=>$spec_key_name,'nums'=>$nums,'price'=>$price,'total_prices'=>$total_prices];
            // 插入
            OrderGood::create($order_goods);
            // 下单减库存
            $this->updateStore($order->id);
            // 没出错，提交事务
            DB::commit();
            $this->ajaxReturn('1',$order->id);
        } catch (\Exception $e) {
            // 出错回滚
            DB::rollBack();
            $this->ajaxReturn('0',$e->getMessage());
        }
    }
    // 检查库存
    private function store($id,$spec_key,$num)
    {
        if ($spec_key == '') {
            $store = Good::where('id',$id)->where('status',1)->value('store');
        }
        else
        {
            $store = GoodSpecPrice::where('good_id',$id)->where('status',1)->where('key',$spec_key)->value('store');
        }
        $store = is_null($store) ? 0 : $store;
        if ($store < $num) {
            return false;
        }
        return true;
    }
}
