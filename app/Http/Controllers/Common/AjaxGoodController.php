<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\Cart;
use App\Models\Good\CouponUser;
use App\Models\Good\Fullgift;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\Promotion;
use App\Models\Good\Timetobuy;
use App\Models\Good\Tuan;
use App\Models\Good\TuanUser;
use App\Models\User\Address;
use App\Models\User\Group;
use App\Models\User\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Storage;

class AjaxGoodController extends BaseController
{
    // 添加购物车
    public function postAddcart(Request $req)
    {
        try {
            // 先清除一天以上的无用购物车，不登陆无法加购物车时不用清除
            // Cart::where('user_id',0)->where('updated_at','<',Carbon::now()->subday())->delete();
            // 清除完成
            $sid = $req->sid;
            $id = $req->gid;
            // 规格key
            $spec_key = $req->spec_key;
            $num = $req->num;
            $userid = $req->uid;
            $new_price = $old_price = $req->gp;
            $type = $req->input('type','');
            // 商品信息
            $good = Good::findOrFail($id);
            // 如果用户已经登录，查以前的购物车
            if (!$userid) {
                $this->ajaxReturn('2',"请先登录！");
            }
            if ($type == 'promotion') {
                // 活动里的，重新计算价格
                $new_price = $this->promotion($new_price,$old_price,$good->prom_id);
            }
            // 当前用户此次登录添加的
            $tmp = Cart::where('user_id',$userid)->where('good_id',$id)->where('good_spec_key',$spec_key)->orderBy('id','desc')->first();
            // 查看有没有在购物车里，有累计数量
            if (!is_null($tmp)) {
                $nums = $num + $tmp->nums;
            }
            else
            {
                $nums = $num;
            }
            // 检查库存
            if ($this->store($id,$spec_key,$nums) == false) {
                $this->ajaxReturn('0','库存不足！');
            }
            $total_prices = $new_price * $nums;
            // 规格信息
            $spec_key_name = GoodSpecPrice::where('good_id',$id)->where('item_id',$spec_key)->value('item_name');
            $a = ['session_id'=>$sid,'user_id'=>$userid,'good_id'=>$id,'good_title'=>$good->title,'good_spec_key'=>$spec_key,'good_spec_name'=>$spec_key_name,'nums'=>$nums,'old_price'=>$old_price,'price'=>$new_price,'total_prices'=>$total_prices,'selected'=>1,'prom_type'=>$good->prom_type,'prom_id'=>$good->prom_id];
            // 查看有没有在购物车里，有累计数量
            if (!is_null($tmp)) {
                Cart::where('id',$tmp->id)->update($a);
            }
            else
            {
                Cart::create($a);
            }
            $this->ajaxReturn('1','加入购物车成功！');
        } catch (\Exception $e) {
            $this->ajaxReturn('0',$e->getMessage());
        }
    }
    // 计算活动价格
    private function promotion($new_price = 0,$old_price = 0,$prom_id = 0)
    {
        $promotion = Promotion::where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->where('id',$prom_id)->first();
        if (!is_null($promotion)) {
            $new_price = $promotion->type === 1 ? ($old_price * $promotion->type_val / 100) : $old_price - $promotion->type_val;
        }
        return $new_price;
    }
    // 修改数量
    public function postChangecart(Request $req)
    {
        try {
            $cid = $req->cid;
            $num = $req->num < 1 ? 1 : $req->num;
            $price = $req->price;
            $carts = Cart::where('id',$cid)->select('good_id','good_spec_key')->first();
            // 商品信息
            $good = Good::findOrFail($carts->good_id);
            // 检查库存
            if ($this->store($carts->good_id,$carts->good_spec_key,$num) == false && $req->type == 1) {
                $this->ajaxReturn('0','库存不足！');return;
            }
            Cart::where('id',$cid)->update(['nums'=>$num,'total_prices'=>$num * $price]);
            $this->ajaxReturn('1',$num);
        } catch (\Exception $e) {
            $this->ajaxReturn('0','修改失败，请稍后再试！');
            // $this->ajaxReturn('0',$e->getMessage());
        }
    }
    // 移除
    public function postRemovecart(Request $req)
    {
        $cid = $req->cid;
        try {
            Cart::where('id',$cid)->delete();
            $this->ajaxReturn('1','删除成功！');
        } catch (\Exception $e) {
            // $this->ajaxReturn('0',$e->getMessage());
            $this->ajaxReturn('0','删除购物车失败，请稍后再试！');
        }
    }
    // 取购物车数量
    public function postCartnums(Request $req)
    {
        $uid = $req->uid;
        if ($uid) {
            $nums = Cart::where('user_id',$uid)->sum('nums');
            $tmp = (string) $nums;
        }
        else
        {
            $tmp = '0';
        }
        exit($tmp);
    }
    // 提交订单功能
    public function postAddorder(Request $req)
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
    // 取消订单功能
    public function postRemoveOrder(Request $req)
    {
        // 事务
        DB::beginTransaction();
        try {
            $id = $req->oid;
            $order = Order::findOrFail($id);
            // 如果订单是正常状态
            if ($order->orderstatus === 1) {
                // 支付过退款到余额里
                if ($order->paystatus) {
                    User::where('id',$order->user_id)->increment('user_money',$order->total_prices);
                    // 消费记录
                    app('com')->consume($order->user_id,$order->id,$order->total_prices,'取消订单（'.$order->order_id.'）退款！',1);
                }
                Order::where('id',$id)->update(['orderstatus'=>0]);
                // 增加库存
                $this->updateStore($id,1);
                // 没出错，提交事务
                DB::commit();
            }
            else
            {
                DB::commit();
                $this->ajaxReturn('0','订单已经完成或关闭不能取消！');
            }
            $this->ajaxReturn('1','取消订单成功！');
        } catch (\Exception $e) {
            // 出错回滚
            DB::rollBack();
            // dd($e->getMessage());
            Storage::disk('log')->prepend('updateOrder.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
            $this->ajaxReturn('0','取消订单失败，请稍后再试！');
        }
    }
    // 确认收货
    public function postConfirmOrder(Request $req)
    {
        // 事务
        try {
            $id = $req->oid;
            Order::where('id',$id)->update(['orderstatus'=>2,'confirm_at'=>date('Y-m-d H:i:s')]);
            $this->ajaxReturn('1','确认收货成功！');
        } catch (\Exception $e) {
            $this->ajaxReturn('0','确认收货失败，请稍后再试！');
        }
    }
}
