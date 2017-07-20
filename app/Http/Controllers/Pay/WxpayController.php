<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Common\BaseController;
use App\Http\Requests;
use App\Models\Order;
use App\Models\Pay;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Storage;

class WxpayController extends BaseController
{
    // 通知页面，放一起了
    public function gateway(Request $req)
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
                Storage::prepend('wxpay.log',json_encode($response->getRequestData()).date('Y-m-d H:i:s'));
                $resData = $response->getRequestData();
                // 库存计算
                $oid = $resData['out_trade_no'];
                $order = Order::where('order_id',$oid)->first();
                $this->updateOrder($order,$paymod = '微信');
                $msg = ['msg'=>'OK','code'=>'SUCCESS'];
            }else{
                //pay fail
                Storage::prepend('wxpay.log',json_encode($response->getRequestData()).date('Y-m-d H:i:s'));
                $msg = ['msg'=>'error','code'=>'FAIL'];
            }
            $tpl = "<xml>
                        <return_code><![CDATA[".$msg['code']."]]></return_code>
                        <return_msg><![CDATA[".$msg['msg']."]]></return_msg>
                        </xml>
                        ";
            return $tpl;
        } catch (\Exception $e) {
            Storage::prepend('wxpay.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
        }
    }
}
