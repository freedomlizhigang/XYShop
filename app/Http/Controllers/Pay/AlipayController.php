<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Common\BaseController;
use App\Http\Requests;
use App\Models\Order;
use App\Models\Pay;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Storage;

class AlipayController extends BaseController
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
        try {
            $response = $request->send();
            $resData = $response->getData();
            if($response->isPaid()){
                /**
                 * Payment is successful
                 */
                // 库存计算
                $oid = $resData['out_trade_no'];
                $order = Order::where('order_id',$oid)->first();
                $this->updateOrder($order,$paymod = '支付宝');
                Storage::prepend('alipay.log',json_encode($resData));
                die('success'); //The notify response should be 'success' only
            }else{
                /**
                 * Payment is not successful
                 */
                Storage::prepend('alipay.log',json_encode($resData));
                die('fail'); //The notify response
            }
        } catch (Exception $e) {
            /**
             * Payment is not successful
             */
            Storage::prepend('alipay.log',$e->getMessage());
            die('fail'); //The notify response
        }
    }
}
