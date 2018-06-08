<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Common\OrderApi;
use App\Http\Controllers\Controller;
use App\Models\Common\Pay;
use App\Models\Good\Order;
use App\Models\User\Recharge;
use App\Models\User\User;
use DB;
use Illuminate\Http\Request;
use Storage;

class WxpayController extends Controller
{
    // 新版回调
    public function gateway(Request $req)
    {
        DB::beginTransaction();
        try {
            $app = app('wechat.payment');
            $response = $app->handlePaidNotify(function($message, $fail){
                // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
                $order = Order::where('order_id',$message['out_trade_no'])->first();
                // Storage::disk('log')->prepend('wxpay.log',json_encode($message).json_encode($fail).json_encode($order).date('Y-m-d H:i:s'));
                if (is_null($order)) { // 如果订单不存在
                    return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
                }
                // return_code 表示通信状态，不代表支付状态
                if ($message['return_code'] === 'SUCCESS') {
                    // 用户是否支付成功
                    if (array_get($message, 'result_code') === 'SUCCESS' && $order->paystatus == '0') {
                        // 写入到日日志里方便查看
                        $updateOrder = OrderApi::updateOrder($order,$paymod = '微信');
                        if (!$updateOrder) {
                            DB::rollback();
                            return $fail('通信失败，请稍后再通知我');
                        }
                    } elseif ($order->paystatus == 1) {
                        return true; // 已经支付成功了就不再更新了
                    }
                } else {
                    return $fail('通信失败，请稍后再通知我');
                }

                if(!$fail)
                {
                    // 用户支付失败
                    return $fail('通信失败，请稍后再通知我');
                }
                return true; // 返回处理完成
            });
            DB::commit();
            return $response;
        } catch (\Throwable $e) {
            DB::rollback();
            Storage::disk('log')->prepend('wxpay.log',$e->getMessage().date('Y-m-d H:i:s'));
        }
    }
    // 充值回调
    public function recharge_gateway(Request $req)
    {
        // Storage::disk('log')->prepend('wxpay.log',json_encode($req->all()).date('Y-m-d H:i:s'));
        DB::beginTransaction();
        try {
            $app = app('wechat.payment');
            $response = $app->handlePaidNotify(function($message, $fail){
                // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
                $order = Recharge::where('order_id',$message['out_trade_no'])->sharedLock()->first();
                // Storage::disk('log')->prepend('wxpay.log',json_encode($message).json_encode($fail).json_encode($order).date('Y-m-d H:i:s'));
                if (is_null($order)) { // 如果订单不存在
                    return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
                }

                // return_code 表示通信状态，不代表支付状态
                if ($message['return_code'] === 'SUCCESS') {
                    // 用户是否支付成功
                    if (array_get($message, 'result_code') === 'SUCCESS' && $order->paystatus == '0') {
                        // 写入到日日志里方便查看
                        User::where('id',$order->user_id)->sharedLock()->increment('user_money',$order->money);
                        Recharge::where('id',$order->id)->update(['paystatus'=>1,'paymod'=>'微信','paytime'=>date('Y-m-d H:i:s')]);
                        // 消费记录
                        app('com')->consume($order->user_id,$order->id,$order->money,'微信充值支付订单（'.$order->order_id.'）',1);
                    } elseif ($order->paystatus == 1) {
                        return true; // 已经支付成功了就不再更新了
                    }
                } else {
                    Recharge::where('order_id',$message['out_trade_no'])->delete();
                    return $fail('通信失败，请稍后再通知我');
                }

                if(!$fail)
                {
                    // 用户支付失败
                    Recharge::where('order_id',$message['out_trade_no'])->delete();
                    return $fail('通信失败，请稍后再通知我');
                }
                return true; // 返回处理完成
            });
            DB::commit();
            return $response;
        } catch (\Throwable $e) {
            DB::rollback();
            Storage::disk('log')->prepend('wxpay.log',$e->getMessage().date('Y-m-d H:i:s'));
        }
    }
}
