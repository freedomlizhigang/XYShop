<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use App\Models\Good\Order;
use App\Models\User\Recharge;
use App\Models\User\User;
use Illuminate\Http\Request;
use Storage;

class AlipayController extends Controller
{
    public $gateway;
    public function __construct()
    {
        $pay = Pay::findOrFail(1);
        $set = json_decode($pay->setting);
        $this->gateway = Omnipay::create('Alipay_LegacyExpress');
        $this->gateway->setSellerEmail($set->alipay_account);
        $this->gateway->setPartner($set->alipay_partner);
        $this->gateway->setKey($set->alipay_key); //For MD5 sign type
        //$this->gateway->setPrivateKey('the_rsa_sign_key'); //For RSA sign type
        //$this->gateway->setAlipayPublicKey('the_alipay_public_key'); //For RSA sign type
        $this->gateway->setReturnUrl(config('app.url').'/alipay/return');
        $this->gateway->setNotifyUrl(config('app.url').'/alipay/gateway');
    }

    // 异步&&同步通知页面，放一起了
    public function gateway(Request $req)
    {
        //Don't use $_REQUEST for may contain $_COOKIE
        $request = $this->gateway->completePurchase();
        $request->setParams($req->all());//Optional
        DB::beginTransaction();
        try {
            $response = $request->send();
            $resData = $response->getData();
            if($response->isPaid()){
                $oid = $resData['out_trade_no'];
                $order = Order::where('order_id',$oid)->first();
                if (!is_null($order) && $order->paystatus == 0) {
                    $updateOrder = OrderApi::updateOrder($order,$paymod = '支付宝');
                    if (!$updateOrder) {
                        DB::rollback();
                        die('fail');
                    }
                }
                else
                {
                    DB::rollback();
                    die('fail');
                }
                Storage::disk('log')->prepend('alipay.log',json_encode($resData));
                DB::commit();
                die('success'); //The notify response should be 'success' only
            }else{
                /**
                 * Payment is not successful
                 */
                Storage::disk('log')->prepend('alipay.log',json_encode($resData));
                DB::rollback();
                die('fail'); //The notify response
            }
        } catch (\Throwable $e) {
            DB::rollback();
            /**
             * Payment is not successful
             */
            Storage::disk('log')->prepend('alipay.log',$e->getMessage());
            die('fail'); //The notify response
        }
    }

    // 充值的异步&&同步通知页面，放一起了
    public function recharge_gateway(Request $req)
    {
        //Don't use $_REQUEST for may contain $_COOKIE
        $request = $this->gateway->completePurchase();
        $request->setParams($req->all());//Optional
        DB::beginTransaction();
        try {
            $response = $request->send();
            $resData = $response->getData();
            if($response->isPaid()){
                /**
                 * Payment is successful
                 */
                // 消费记录
                $oid = $resData['out_trade_no'];
                $order = Recharge::where('order_id',$oid)->first();
                // 支付成功，并且没有改状态时，给会员增加余额
                if ($order->paystatus == 0) {
                    User::where('id',$order->user_id)->increment('user_money',$order->money);
                    Recharge::where('id',$order->id)->update(['paystatus'=>1,'paymod'=>'支付宝','paytime'=>date('Y-m-d H:i:s')]);
                    // 消费记录
                    app('com')->consume($order->user_id,$order->id,$order->money,'支付宝充值支付订单（'.$order->order_id.'）',1);
                }
                DB::commit();
                Storage::disk('log')->prepend('alipay.log',json_encode($resData));
                die('success'); //The notify response should be 'success' only
            }else{
                /**
                 * Payment is not successful
                 */
                Storage::disk('log')->prepend('alipay.log',json_encode($resData));
                die('fail'); //The notify response
            }
        } catch (\Throwable $e) {
            /**
             * Payment is not successful
             */
            DB::rollback();
            Storage::disk('log')->prepend('alipay.log',$e->getMessage());
            die('fail'); //The notify response
        }
    }
}
