<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Common\BaseController;
use App\Models\Common\Pay;
use App\Models\Good\Order;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Storage;
use DB;

class WxpayController extends BaseController
{
    // 新版回调
    public function gateway(Request $req)
    {
        DB::beginTransaction();
        try {
            $app = app('wechat');
            $response = $app->payment->handleNotify(function($notify, $successful){
                // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
                $order = Order::where('order_id',$notify->out_trade_no)->first(); 
                if (is_null($order)) { // 如果订单不存在
                    return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
                }
                // 如果订单存在
                // 检查订单是否已经更新过支付状态
                if ($order->paystatus == 1) {
                    return true; // 已经支付成功了就不再更新了
                }
                // 用户是否支付成功
                if ($successful && $order->paystatus == '0') {
                    // 写入到日日志里方便查看
                    Storage::disk('log')->prepend('wxpay.log',json_encode($notify).json_encode($successful).date('Y-m-d H:i:s'));
                    Storage::disk('log')->prepend('wxpay.log',json_encode($order).json_encode($successful).date('Y-m-d H:i:s'));
                    // 消费记录
                    $this->updateOrder($order,$paymod = '微信');
                } 
                if(!$successful)
                { 
                    // 用户支付失败
                    return 'FAIL';
                }
                return true; // 返回处理完成
            });
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            Storage::disk('log')->prepend('wxpay.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
        }
    }
    // 通知页面，放一起了
    public function gateway_old(Request $req)
    {
        try {
            $gateway = Omnipay::create('WechatPay');
            $set = json_decode(Pay::findOrFail(2)->setting);
            $gateway->setAppId($set->appid);
            $gateway->setMchId($set->mchid);
            $gateway->setApiKey($set->appkey);
            $response = $gateway->completePurchase([
                'request_params' => file_get_contents('php://input')
            ])->send();
            // 判断是否成功
            if ($response->isPaid()) {
                //pay success
                // 写入到日日志里方便查看
                Storage::disk('log')->prepend('wxpay.log',json_encode($response->getRequestData()).date('Y-m-d H:i:s'));
                $resData = $response->getRequestData();
                // 库存计算
                $oid = $resData['out_trade_no'];
                $order = Order::where('order_id',$oid)->first();
                $this->updateOrder($order,$paymod = '微信');
                $msg = ['msg'=>'OK','code'=>'SUCCESS'];
            }else{
                //pay fail
                Storage::disk('log')->prepend('wxpay.log',json_encode($response->getRequestData()).date('Y-m-d H:i:s'));
                $msg = ['msg'=>'error','code'=>'FAIL'];
            }
            $tpl = "<xml>
                        <return_code><![CDATA[".$msg['code']."]]></return_code>
                        <return_msg><![CDATA[".$msg['msg']."]]></return_msg>
                        </xml>
                        ";
            return $tpl;
        } catch (\Exception $e) {
            Storage::disk('log')->prepend('wxpay.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
        }
    }
}
