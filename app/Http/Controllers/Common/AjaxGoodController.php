<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\OrderApi;
use App\Http\Controllers\Controller;
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
use App\Models\User\SignConfig;
use App\Models\User\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;

class AjaxGoodController extends Controller
{
    // 添加购物车
    public function postAddcart(Request $req)
    {
        try {
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
            if ($spec_key == '') {
                $new_price = $old_price = $good->shop_price;
            }
            else
            {
                $spec_key_price = GoodSpecPrice::where('good_id',$id)->where('item_id',$spec_key)->value('price');
                if (is_null($spec_key_price)) {
                    $this->ajaxReturn('0',"规格错误！");
                }
                $new_price = $old_price = $spec_key_price;
            }
            // 如果用户已经登录，查以前的购物车
            if (!$userid) {
                $this->ajaxReturn('2',"请先登录！");
            }
            // 活动 ？重新计算价格 ：计算会员折扣多少
            if ($type == 'promotion') {
                $new_price = $this->promotion($old_price,$good->prom_id);
            }
            else
            {
                // 算折扣，改为按用户组计算
                try {
                    $gid = User::where('id',$userid)->value('gid');
                    $discount = Group::where('id',$gid)->value('discount');
                } catch (\Throwable $e) {
                    $discount = 100;
                }
                $new_price = ($old_price * $discount) / 100;
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
            if (OrderApi::store($id,$spec_key,$nums) == false) {
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
        } catch (\Throwable $e) {
            $this->ajaxReturn('0',$e->getMessage());
        }
    }
    // 计算活动价格
    private function promotion($old_price = 0,$prom_id = 0)
    {
        $promotion = Promotion::where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->where('id',$prom_id)->first();
        $new_price = $old_price;
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
            $new_price = $old_price = $req->price;
            $carts = Cart::where('id',$cid)->select('good_id','good_spec_key','user_id')->first();
            // 商品信息
            $good = Good::findOrFail($carts->good_id);
            // 价格重新计算，不用传过来的
            if ($carts->good_spec_key == '') {
                $new_price = $old_price = $good->shop_price;
            }
            else
            {
                $spec_key_price = GoodSpecPrice::where('good_id',$carts->good_id)->where('item_id',$carts->good_spec_key)->value('price');
                if (is_null($spec_key_price)) {
                    $this->ajaxReturn('0',"规格错误！");
                }
                $new_price = $old_price = $spec_key_price;
            }
            // 检查库存
            if (OrderApi::store($carts->good_id,$carts->good_spec_key,$num) == false && $req->type == 1) {
                $this->ajaxReturn('0','库存不足！');return;
            }
            // 此时商品活动 ？重新计算价格 ：计算会员折扣多少
            if ($good->prom_type == 1) {
                $new_price = $this->promotion($old_price,$good->prom_id);
            }
            // 算折扣
            else
            {
                // 算折扣，改为按用户组计算
                try {
                    $gid = User::where('id',$carts->user_id)->value('gid');
                    $discount = Group::where('id',$gid)->value('discount');
                } catch (\Throwable $e) {
                    $discount = 100;
                }
                $new_price = ($old_price * $discount) / 100;
            }
            $total_prices = $num * $new_price;
            Cart::where('id',$cid)->update(['nums'=>$num,'old_price'=>$old_price,'price'=>$new_price,'total_prices'=>$total_prices]);
            $this->ajaxReturn('1',$num);
        } catch (\Throwable $e) {
            // $this->ajaxReturn('0',$e->getMessage());
            $this->ajaxReturn('0','修改失败，请稍后再试！');
        }
    }
    // 移除
    public function postRemovecart(Request $req)
    {
        $cid = $req->cid;
        try {
            Cart::where('id',$cid)->delete();
            $this->ajaxReturn('1','删除成功！');
        } catch (\Throwable $e) {
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
        // 事务
        DB::beginTransaction();
        try {
            $uid = $req->uid;
            if (!$uid) {
                DB::rollback();
                $this->ajaxReturn('2',"请先登录！");
            }
            // 判断是否选择送货地址
            if ((empty($req->aid) && empty($req->ziti)) || ($req->aid == 0 && $req->ziti == 0)) {
                DB::rollback();
                $this->ajaxReturn('0','请选择送货地址！');
            }
            // 找出所有 购物车
            $ids = explode(',', trim($req->cid,','));
            if (count($ids) == 0) {
                DB::rollback();
                $this->ajaxReturn('0','购物车里是空的，请先购物！');
            }
            // 所有产品总价
            $carts = Cart::whereIn('id',$ids)->orderBy('updated_at','desc')->get();
            // 在这里检查库存,循环检查一下是否总价是对的
            foreach ($carts as $v) {
                if($v->total_prices != $v->nums * $v->price)
                {
                    Cart::where('id',$v->id)->update(['total_prices'=>$v->nums * $v->price]);
                }
                // 查看是不是活动中的商品，团购-限时-限量
                if(OrderApi::store($v->good_id,$v->good_spec_key,$v->nums) == false){
                    DB::rollback();
                    $this->ajaxReturn('0',$v->good_title.'，库存不足！');
                }
            }
            $old_prices = $total_prices = Cart::whereIn('id',$ids)->sum('total_prices');
            // 创建订单
            $order_id = app('com')->orderid();
            // 查出优惠券优惠多少
            $yh_price = 0;
            $yhq_id = isset($req->yid) ? $req->yid : 0;
            if ($yhq_id) {
                $yh = CouponUser::where('id',$req->yid)->first();
                $yh_price = $yh->coupon->lessprice;
                $total_prices = $old_prices - $yh_price;
            }
            // 没有优惠券时查有没有赠品
            else
            {
                $mz = Fullgift::with(['good'=>function($q){
                        $q->select('id','shop_price','title');
                    }])->where('price','<=',$total_prices)->where('status',1)->where('endtime','>=',date('Y-m-d H:i:s'))->where('store','>',0)->orderBy('price','desc')->sharedLock()->first();
            }
            $area = Address::where('id',$req->aid)->value('area');
            $points = $req->input('point',0);
            $cash = SignConfig::where('id',1)->value('cash');
            $points_money = $points/$cash;
            $total_prices = $total_prices - $points_money;
            // 扣掉积分
            if (User::where('id',$uid)->value('points') < $points) {
                DB::rollback();
                $this->ajaxReturn('0','积分不足了，请重新选择积分数量！');
            }
            User::where('id',$uid)->decrement('points',$points);
            $order = ['order_id'=>$order_id,'user_id'=>$uid,'yhq_id'=>$yhq_id,'yh_price'=>$yh_price,'points'=>$points,'points_money'=>$points_money,'old_prices'=>$old_prices,'total_prices'=>$total_prices,'create_ip'=>$req->ip(),'address_id'=>$req->aid,'ziti'=>$req->ziti,'area'=>$area,'mark'=>$req->mark];
        } catch (\Throwable $e) {
            DB::rollback();
            // $this->ajaxReturn('0','添加失败，请稍后再试！');
            $this->ajaxReturn('0',$e->getMessage());
        }
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
            // 下单减库存，一定要放在加订单商品后边
            OrderApi::updateStore($order->id);
            // 没出错，提交事务
            DB::commit();
            $this->ajaxReturn('1',$order->id);
        } catch (\Throwable $e) {
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
                    User::where('id',$order->user_id)->sharedLock()->increment('user_money',$order->total_prices);
                    // 消费记录
                    app('com')->consume($order->user_id,$order->id,$order->total_prices,'取消订单（'.$order->order_id.'）退款！',1);
                }
                // 返还积分
                User::where('id',$order->user_id)->increment('points',$order->points);
                Order::where('id',$id)->update(['orderstatus'=>0]);
                // 增加库存
                OrderApi::updateStore($id,1);
                // 没出错，提交事务
                DB::commit();
            }
            else
            {
                DB::commit();
                $this->ajaxReturn('0','订单已经完成或关闭不能取消！');
            }
            $this->ajaxReturn('1','取消订单成功！');
        } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
            $this->ajaxReturn('0','确认收货失败，请稍后再试！');
        }
    }
    // 完善订单信息
    public function postEditorder(Request $req)
    {
        DB::beginTransaction();
        try {
            $oid = $req->oid;
            $area = Address::where('id',$req->aid)->value('area');
            Order::where('id',$oid)->update(['area'=>$area,'address_id'=>$req->aid,'ziti'=>$req->ziti,'mark'=>$req->mark]);
            DB::commit();
            $this->ajaxReturn('1',$req->oid);
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            Log::warning('抢购订单修改失败记录：',['line'=>$e->getLine(),'msg'=>$e->getMessage()]);
            // $this->ajaxReturn('0','添加失败，请稍后再试！');
            $this->ajaxReturn('0',$e->getMessage());
        }
    }
}
