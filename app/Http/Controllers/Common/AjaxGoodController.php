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
            $price = $req->gp;
            // 商品信息
            $good = Good::findOrFail($id);
            // 如果用户已经登录，查以前的购物车
            if ($userid) {
                // 查看是不是活动中的商品，团购-限时-限量
                // 限时，限购
                if ($good->prom_type === 1) {
                    // 查出来是哪个活动
                    $timetobuy = Timetobuy::where('delflag',1)->where('status',1)->where('id',$good->prom_id)->first();
                    // 看限时
                    if (!is_null($timetobuy)) {
                        if (strtotime($timetobuy->endtime) < time()) {
                            $this->ajaxReturn('0','限时抢购，已经结束！');
                        }
                        // 购物车里有，过往30天订单里有，都算已经购买过
                        if (Cart::where('good_id',$id)->where('good_spec_key',$spec_key)->where('user_id',$userid)->sum('nums') >= $timetobuy->buy_max || OrderGood::where('good_id',$id)->where('good_spec_key',$spec_key)->where('user_id',$userid)->where('status',1)->where('created_at','>',Carbon::now()->subday(30))->sum('nums') >= $timetobuy->buy_max) {
                            $this->ajaxReturn('0','限量购买，超过限制份数！');
                        }
                        if ($num > $timetobuy->buy_max) {
                            $this->ajaxReturn('0','限量购买，本次超过限制份数！');
                        }
                    }
                }
                // 团购
                if ($good->prom_type === 2) {
                    if ($num > 1) {
                        $this->ajaxReturn('0','团购限量购买，超过限制份数！');
                    }
                    if(!is_null(TuanUser::where('user_id',$userid)->where('t_id',$good->prom_id)->where('status',1)->first()) || !is_null(Cart::where('user_id',$userid)->where('good_id',$id)->where('good_spec_key',$spec_key)->where('prom_type',2)->where('prom_id',$good->prom_id)->first()))
                    {
                        $this->ajaxReturn('0','参加过，请不要重复参加！');
                    }
                    // 没参过团的
                    if(Tuan::where('delflag',1)->where('status',1)->where('id',$good->prom_id)->orderBy('sort','desc')->orderBy('id','desc')->value('store') == 0)
                    {
                        $this->ajaxReturn('0','已经满员，等待下次机会吧！');
                    }
                }
                // 当前用户此次登录添加的
                $tmp = Cart::where('session_id',$sid)->where('user_id',$userid)->where('good_id',$id)->where('good_spec_key',$spec_key)->orderBy('id','desc')->first();
                // 如果没有，看以前有没有添加过这类商品
                if(is_null($tmp))
                {
                    $tmp = Cart::where('user_id',$userid)->where('good_id',$id)->where('good_spec_key',$spec_key)->orderBy('id','desc')->first();
                }
            }
            else
            {
                $this->ajaxReturn('2',"请先登录！");
            }
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
            $total_prices = $price * $nums;
            // 规格信息
            $spec_key_name = GoodSpecPrice::where('good_id',$id)->where('item_id',$spec_key)->value('item_name');
            $a = ['session_id'=>$sid,'user_id'=>$userid,'good_id'=>$id,'good_title'=>$good->title,'good_spec_key'=>$spec_key,'good_spec_name'=>$spec_key_name,'nums'=>$nums,'price'=>$price,'total_prices'=>$total_prices,'selected'=>1,'prom_type'=>$good->prom_type,'prom_id'=>$good->prom_id];
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
            // 查看是不是活动中的商品，团购-限时-限量
            // 限时，限购
            if ($carts->prom_type === 1) {
                // 查出来是哪个活动
                $timetobuy = Timetobuy::where('delflag',1)->where('status',1)->where('id',$good->prom_id)->first();
                // 看限时
                if (!is_null($timetobuy)) {
                    if (strtotime($timetobuy->endtime) < time()) {
                        $this->ajaxReturn('0','限时抢购，已经结束！');
                    }
                    // 购物车里有，过往30天订单里有，都算已经购买过
                    if ($carts->nums >= $timetobuy->buy_max || OrderGood::where('good_id',$carts->good_id)->where('good_spec_key',$carts->good_spec_key)->where('user_id',$carts->user_id)->where('status',1)->where('created_at','>',Carbon::now()->subday(30))->sum('nums') >= $timetobuy->buy_max) {
                        $this->ajaxReturn('0','限量购买，超过限制份数！');
                    }
                    if ($num > $timetobuy->buy_max) {
                        $this->ajaxReturn('0','限量购买，本次超过限制份数！');
                    }
                }
            }
            // 团购
            if ($good->prom_type === 2) {
                if ($num > 1) {
                    $this->ajaxReturn('0','团购限量购买，超过限制份数！');
                }
            }
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
                // 限时，限购--是不是已经满员
                if ($v->prom_type === 1) {
                    // 查出来是哪个活动
                    $timetobuy = Timetobuy::where('delflag',1)->where('status',1)->where('id',$v->prom_id)->first();
                    // 看限时
                    if (!is_null($timetobuy)) {
                        if (strtotime($timetobuy->endtime) < time()) {
                            $this->ajaxReturn('0','活动已经结束，请删除（'.$v->good_title.'）！');
                        }
                        if ($timetobuy->good_num <= $timetobuy->buy_num) {
                            $this->ajaxReturn('0',$v->good_title.' - 已被抢完，请删除！');
                        }
                    }
                    else
                    {
                        $this->ajaxReturn('0','活动已经结束，请删除（'.$v->good_title.'）！');
                    }
                }
                // 团购，是不是已经满员
                if ($v->prom_type === 2 && (Tuan::where('delflag',1)->where('status',1)->where('id',$v->prom_id)->orderBy('sort','desc')->orderBy('id','desc')->value('store') === 0 || $v->nums > 1)) {
                    $this->ajaxReturn('0',$v->good_title.'--团购已满员，请删除！！');
                }
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
                    }])->where('price','<=',$prices)->where('status',1)->where('endtime','>=',date('Y-m-d H:i:s'))->orderBy('price','desc')->first();
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
                $order_goods[$k] = ['user_id'=>$uid,'order_id'=>$order->id,'good_id'=>$v->good_id,'good_title'=>$v->good_title,'good_spec_key'=>$v->good_spec_key,'good_spec_name'=>$v->good_spec_name,'nums'=>$v->nums,'price'=>$v->price,'total_prices'=>$v->total_prices,'created_at'=>$date,'updated_at'=>$date,'prom_type'=>$v->prom_type,'prom_id'=>$v->prom_id];
                $clear_ids[] = $v->id;
            }
            // 如果有赠品，加上赠品
            if (isset($mz) && !is_null($mz) && !is_null($mz->good)) {
                $mz_good_spec = GoodSpecPrice::where('good_id',$mz->good_id)->orderBy('price','asc')->first();
                $good_spec_key = is_null($mz_good_spec) ? '' : $mz_good_spec->good_spec_key;
                $good_spec_name = is_null($mz_good_spec) ? '' : $mz_good_spec->good_spec_name;
                $price = is_null($mz_good_spec) ? $mz->good->price : $mz_good_spec->price;
                $order_goods[] = ['user_id'=>$uid,'order_id'=>$order->id,'good_id'=>$mz->good_id,'good_title'=>'赠品-'.$mz->good->title,'good_spec_key'=>$good_spec_key,'good_spec_name'=>$good_spec_name,'nums'=>1,'price'=>$price,'total_prices'=>0,'created_at'=>$date,'updated_at'=>$date,'prom_type'=>3,'prom_id'=>$mz->id];
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
            // 支付过退款到余额里
            if ($order->paystatus) {
                User::where('id',$order->user_id)->increment('user_money',$order->total_prices);
                // 消费记录
                app('com')->consume($order->user_id,$order->id,$order->total_prices,'取消订单退款！',1);
            }
            Order::where('id',$id)->update(['orderstatus'=>0]);
            // 增加库存
            $this->updateStore($id,1);
            // 没出错，提交事务
            DB::commit();
            $this->ajaxReturn('1');
        } catch (\Exception $e) {
            // 出错回滚
            DB::rollBack();
            // dd($e->getMessage());
            Storage::disk('log')->prepend('updateOrder.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
            $this->ajaxReturn('0','取消订单失败，请稍后再试！');
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
            $store = GoodSpecPrice::where('good_id',$id)->where('item_id',$spec_key)->value('store');
        }
        $store = is_null($store) ? 0 : $store;
        if ($store < $num) {
            return false;
        }
        return true;
    }
    // 更新库存，活动内容同时更新
    private function updateStore($oid = '',$type = 0)
    {
        if ($type) {
            // 加库存，先找出来所有的商品ID与商品属性ID
            $goods = OrderGood::where('order_id',$oid)->where('status',1)->select('id','good_id','good_spec_key','nums','prom_id','prom_type','user_id')->get();
            // 循环，判断是直接减商品库存，还是减带属性的库存
            foreach ($goods as $k => $v) {
                if ($v->good_spec_key != '') {
                    GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->spec_key)->increment('store',$v->nums);
                }
                Good::where('id',$v->good_id)->increment('store',$v->nums); 
                // 加销量
                Good::where('id',$v->good_id)->decrement('sales',$v->nums);
                // 查看活动情况，参加人加一，数量减一
                if ($v->prom_type === 1) {
                    Timetobuy::where('id',$v->prom_id)->increment('good_num',$v->nums);
                    Timetobuy::where('id',$v->prom_id)->decrement('buy_num');
                    Timetobuy::where('id',$v->prom_id)->decrement('order_num',$v->nums);
                }
                if ($v->prom_type === 2) {
                    TuanUser::create(['status'=>1,'t_id'=>$v->prom_id,'user_id'=>$v->user_id]);
                    Tuan::where('id',$v->prom_id)->increment('store',$v->nums);
                    Tuan::where('id',$v->prom_id)->decrement('buy_num',$v->nums);
                }
                if ($v->prom_type === 3) {
                    Fullgift::where('id',$v->prom_id)->increment('store',$v->nums);
                }
            }
        }
        else
        {
            // 减库存，先找出来所有的商品ID与商品属性ID
            $goods = OrderGood::where('order_id',$oid)->where('status',1)->select('id','good_id','good_spec_key','nums','prom_id','prom_type','user_id')->get();
            // 循环，判断是直接减商品库存，还是减带属性的库存
            foreach ($goods as $k => $v) {
                if ($v->good_spec_key != '') {
                    GoodSpecPrice::where('good_id',$v->good_id)->where('item_id',$v->spec_key)->decrement('store',$v->nums);
                }
                Good::where('id',$v->good_id)->decrement('store',$v->nums); 
                // 加销量
                Good::where('id',$v->good_id)->increment('sales',$v->nums);
                // 查看活动情况，参加人加一，数量减一
                if ($v->prom_type === 1) {
                    Timetobuy::where('id',$v->prom_id)->decrement('good_num',$v->nums);
                    Timetobuy::where('id',$v->prom_id)->increment('buy_num');
                    Timetobuy::where('id',$v->prom_id)->increment('order_num',$v->nums);
                }
                if ($v->prom_type === 2) {
                    TuanUser::where('t_id',$v->prom_id)->where('user_id',$v->user_id)->update(['status'=>0]);
                    Tuan::where('id',$v->prom_id)->decrement('store',$v->nums);
                    Tuan::where('id',$v->prom_id)->increment('buy_num',$v->nums);
                }
                if ($v->prom_type === 3) {
                    Fullgift::where('id',$v->prom_id)->decrement('store',$v->nums);
                }
            }
        }
    }
    // 关单，有条件就做成定时任务，这里是个大坑
    private function closeOrder()
    {
        DB::beginTransaction();
        try {
            $order_list = Order::where('orderstatus',1)->where('paystatus',0)->where('created_at','<',Carbon::now()->subday())->pluck('id');
            foreach ($order_list as $o) {
                // 增加库存
                $this->updateStore($o,1);
            }
            // 关掉一天以前的未付款订单，同时把团购或者其它活动里的数量增加回去
            Order::where('orderstatus',1)->where('paystatus',0)->where('created_at','<',Carbon::now()->subday())->update(['orderstatus'=>0]);
            // 已经支付、发货的七天自动完成
            Order::where('orderstatus',1)->where('shipstatus',1)->where('paystatus',1)->where('ship_at','<',Carbon::now()->subday(7))->update(['orderstatus'=>2,'confirm_at'=>date('Y-m-d H:i:s')]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Storage::disk('log')->prepend('updateOrder.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
            $this->ajaxReturn('0','提交失败，请稍后再试！');
        }
    }
}
