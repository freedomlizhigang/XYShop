<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Good\Cart;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\User\User;
use DB;
use Illuminate\Http\Request;
use Storage;

class BaseController extends Controller
{
    public $theme = 'home';
    public function __construct()
    {
        session()->put('member',(object)['id'=>1,'points'=>10000,'openid'=>'osxIs0mmwpMH5jHrcRFESwSEnW4k']);
        // $this->theme = isset(cache('config')['theme']) && cache('config')['theme'] != null ? cache('config')['theme'] : 'mobile';
        $this->theme = 'mobile';
    }
    // 更新购物车
	public function updateCart($uid)
    {
        $sid = session()->getId();
        // 找出老数据库购物车里的东西
        $old_carts = Cart::where('user_id',$uid)->get();
        // 把session_id更新过来
        Cart::where('user_id',$uid)->update(['session_id'=>$sid]);
        $old_carts = $old_carts->keyBy('good_id')->toArray();
        // 找出新加入购物车的东西
        $new_carts = Cart::where('session_id',$sid)->get();
        // 先循环来整合现在session_id与数据库的cart
        if ($new_carts->count() > 0) {
            $tmp = [];
            foreach ($new_carts as $k => $v) {
                $gid = $v->good_id;
                // 判断一下现在的session_id里有没有同一款产品
                if (isset($old_carts[$gid]) && $old_carts[$gid]['good_spec_key'] == $v['good_spec_key']) {
                    $nums = $v->nums + $old_carts[$gid]['nums'];
                    $price = $v->price;
                    $v = ['session_id'=>$sid,'user_id'=>$uid,'good_id'=>$gid,'good_title'=>$v->good_title,'good_spec_key'=>$v->good_spec_key,'good_spec_name'=>$v->good_spec_name,'nums'=>$nums,'price'=>$price,'total_prices'=>$nums * $price,'type'=>$v->type,'created_at'=>$v->created_at];
                    // 把旧的删除，新的更新
                    Cart::where('user_id',$uid)->where('good_id',$gid)->where('good_spec_key',$v['good_spec_key'])->delete();
                    Cart::create($v);
                }
                else
                {
                    $v = ['user_id'=>$uid];
                    Cart::where('session_id',$sid)->where('good_id',$gid)->update($v);
                }
            }
        }
    }
    // 消费记录
    public function updateOrder($order = '',$paymod = '余额')
    {
        // 事务
        DB::beginTransaction();
        try {
            Order::where('id',$order->id)->update(['paystatus'=>1,'pay_name'=>$paymod]);
            User::where('id',$order->user_id)->increment('points',$order->total_prices);
            // 消费记录
            app('com')->consume($order->user_id,$order->id,$order->total_prices,$paymod.'支付订单');
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
