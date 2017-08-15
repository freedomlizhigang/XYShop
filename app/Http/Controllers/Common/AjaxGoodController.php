<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\Cart;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Manzeng;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\YhqUser;
use App\Models\User\Address;
use App\Models\User\Group;
use App\Models\User\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

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
                // 查看是否限时，限购
                if ($good->isxs && strtotime($good->endtime) < time()) {
                    $this->ajaxReturn('0','限时抢购，已经结束！');
                }
                // 购物车里有，过往30天订单里有，都算已经购买过
                if ($good->isxl && (Cart::where('good_id',$id)->where('user_id',$userid)->sum('nums') >= $good->xlnums || OrderGood::where('good_id',$id)->where('user_id',$userid)->where('status',1)->where('created_at','>',Carbon::now()->subday(30))->sum('nums') >= $good->xlnums)) {
                    $this->ajaxReturn('0','限量购买，已购买过了！');
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
            $a = ['session_id'=>$sid,'user_id'=>$userid,'good_id'=>$id,'good_title'=>$good->title,'good_spec_key'=>$spec_key,'good_spec_name'=>$spec_key_name,'nums'=>$nums,'price'=>$price,'total_prices'=>$total_prices,'selected'=>1,'type'=>0];
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
            // 检查库存
            $carts = Cart::where('id',$cid)->select('good_id','good_spec_key')->first();
            if ($this->store($carts->good_id,$carts->good_spec_key,$num) == false && $req->type == 1) {
                $this->ajaxReturn('0','库存不足！');return;
            }
            Cart::where('id',$cid)->update(['nums'=>$num,'total_prices'=>$num * $price]);
            $this->ajaxReturn('1',$num);
        } catch (\Exception $e) {
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
            if (!isset($req->aid) || !isset($req->ziti)) {
                $this->ajaxReturn('0','请选择送货地址！');
            }
            // 找出所有 购物车
            $ids = explode(',', trim($req->cid,','));
            if (count($ids) == 0) {
                $this->ajaxReturn('0','购物车里是空的，请先购物！');
            }
            // 关掉一天以前的未付款订单
            Order::where('orderstatus',1)->where('paystatus',0)->where('created_at','<',Carbon::now()->subday())->update(['orderstatus'=>0]);
            // 三天前的自动完成
            Order::where('orderstatus',1)->where('shipstatus',1)->where('paystatus',1)->where('ship_at','<',Carbon::now()->subday(3))->update(['orderstatus'=>2]);
            // 所有产品总价
            $old_prices = Cart::whereIn('id',$ids)->sum('total_prices');
            $carts = Cart::whereIn('id',$ids)->orderBy('updated_at','desc')->get();
            // 在这里检查库存
            foreach ($carts as $v) {
                if($this->store($v->good_id,$v->good_spec_key,$v->nums) == false);
                $this->ajaxReturn('0',$v->good_title.'，库存不足！');
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
                $yh = YhqUser::where('id',$req->yid)->first();
                $yh_price = $yh->yhq->lessprice;
                $prices = $prices - $yh_price;
            }
            // 没有优惠券时查有没有赠品
            else
            {
                $mz = Manzeng::with(['good'=>function($q){
                        $q->select('id','price','title');
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
            $date = Carbon::now();
            foreach ($carts as $k => $v) {
                $order_goods[$k] = ['user_id'=>$uid,'order_id'=>$order->id,'good_id'=>$v->good_id,'good_title'=>$v->good_title,'good_spec_key'=>$v->good_spec_key,'good_spec_name'=>$v->good_spec_name,'nums'=>$v->nums,'price'=>$v->price,'total_prices'=>$v->total_prices,'created_at'=>$date,'updated_at'=>$date];
                $clear_ids[] = $v->id;
            }
            // 如果有赠品，加上赠品
            if (isset($mz) && !is_null($mz) && !is_null($mz->good)) {
                $mz_good_spec = GoodSpecPrice::where('good_id',$mz->good_id)->orderBy('price','asc')->first();
                $good_spec_key = is_null($mz_good_spec) ? '' : $mz_good_spec->good_spec_key;
                $good_spec_name = is_null($mz_good_spec) ? '' : $mz_good_spec->good_spec_name;
                $price = is_null($mz_good_spec) ? $mz->good->price : $mz_good_spec->price;
                $order_goods[] = ['user_id'=>$uid,'order_id'=>$order->id,'good_id'=>$mz->good_id,'good_title'=>'赠品-'.$mz->good->title,'good_spec_key'=>$good_spec_key,'good_spec_name'=>$good_spec_name,'nums'=>1,'price'=>$price,'total_prices'=>0,'created_at'=>$date,'updated_at'=>$date];
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
            Storage::prepend('updateOrder.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
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
}
