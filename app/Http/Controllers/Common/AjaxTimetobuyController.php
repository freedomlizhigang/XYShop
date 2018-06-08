<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\OrderApi;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\Timetobuy;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;

class AjaxTimetobuyController extends Controller
{
    // 提交订单功能
    public function postCreateorder(Request $req)
    {
        DB::beginTransaction();
        try {
            $sid = $req->sid;
            $id = $req->gid;
            // 规格key
            $spec_key = $req->spec_key;
            $num = $req->num;
            $userid = $req->uid;
            $price = $old_price = $req->gp;
            // 商品信息
            $good = Good::findOrFail($id);
            // 检查库存
            if (OrderApi::store($id,$spec_key,$num) == false) {
                DB::rollback();
                $this->ajaxReturn('0','库存不足！');
            }
            // 如果用户已经登录，查以前的购物车
            if (!$userid) {DB::rollback();$this->ajaxReturn('2',"请先登录！");}
            // 限时，限购
            $timetobuy = Timetobuy::where('delflag',1)->where('status',1)->where('id',$good->prom_id)->sharedLock()->first();
            // 看限时
            if (!is_null($timetobuy)) {
                if (strtotime($timetobuy->endtime) < time() || $timetobuy->good_num <= 0) {
                    DB::rollback();
                    $this->ajaxReturn('0','限时抢购，已经结束！');
                }
                // 过往30天订单里有，都算已经购买过
                if (OrderGood::where('good_id',$id)->where('good_spec_key',$spec_key)->where('user_id',$userid)->where('status',1)->where('created_at','>',Carbon::now()->subday(30))->sum('nums') >= $timetobuy->buy_max) {
                    DB::rollback();
                    $this->ajaxReturn('0','限量购买，超过限制份数！');
                }
                if ($num > $timetobuy->buy_max) {
                    DB::rollback();
                    $this->ajaxReturn('0','限量购买，本次超过限制份数！');
                }
                // 重新计算价格
                $price = $old_price = $timetobuy->price;
            }
            else
            {
                DB::rollback();
                $this->ajaxReturn('0','限时抢购，已经结束！');
            }
            $old_price = $old_price * $num;
            // 价格
            $prices = $price * $num;
            // 规格信息
            $spec_key_name = GoodSpecPrice::where('good_id',$id)->where('item_id',$spec_key)->value('item_name');
            // 创建订单
            $order_id = app('com')->orderid();
            $order = ['order_id'=>$order_id,'user_id'=>$userid,'yhq_id'=>0,'yh_price'=>0,'old_prices'=>$old_price,'total_prices'=>$prices,'create_ip'=>$req->ip(),'address_id'=>0,'ziti'=>0,'area'=>'','mark'=>'','prom_type'=>1];
        } catch (\Throwable $e) {
            DB::rollback();
            // $this->ajaxReturn('0','添加失败，请稍后再试！');
            $this->ajaxReturn('0',$e->getMessage());
        }
        try {
            $order = Order::create($order);
            // 组合order_goods数组
            $order_goods = [];
            $date = date('Y-m-d H:i:s');
            $order_goods = ['user_id'=>$userid,'order_id'=>$order->id,'good_id'=>$good->id,'good_title'=>$good->title,'good_spec_key'=>$spec_key,'good_spec_name'=>$spec_key_name,'nums'=>$num,'old_price'=>$old_price,'price'=>$price,'total_prices'=>$prices,'created_at'=>$date,'updated_at'=>$date,'prom_type'=>$good->prom_type,'prom_id'=>$good->prom_id];
            // 如果商品是空回滚
            if (count($order_goods) == 0) {
                // 出错回滚
                DB::rollBack();
                $this->ajaxReturn('0','请不要重复提交！');
            }
            // 插入
            OrderGood::insert($order_goods);
            // 下单减库存，一定要放在加订单商品后边
            OrderApi::updateStore($order->id);
            // 没出错，提交事务
            DB::commit();
            $this->ajaxReturn('1',$order->id);
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            Log::warning('抢购订单失败记录：',['line'=>$e->getLine(),'msg'=>$e->getMessage()]);
            // $this->ajaxReturn('0','添加失败，请稍后再试！');
            $this->ajaxReturn('0',$e->getMessage());
        }
    }

}
