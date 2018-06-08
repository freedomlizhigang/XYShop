<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Common\OrderApi;
use App\Http\Controllers\Controller;
use App\Models\Common\Pay;
use App\Models\Good\Order;
use App\Models\User\User;
use DB;
use EasyWeChat\Payment\Order as WxOrder;
use Illuminate\Http\Request;
use Log;
use Omnipay\Omnipay;
use QrCode;
use Storage;

class PayController extends Controller
{
    // 真正的支付
    public function getTopay(Request $req,$oid)
    {
        try {
            if ($req->pay == '') {
                return back()->with('message','请选择支付方式');
            }
            $pay = Pay::findOrFail($req->pay);
            // 是否支付过
            $paystatus = Order::where('id',$oid)->value('paystatus');
            if ($paystatus) {
                return back()->with('message','支付过了！');
            }
            // 根据支付方式调用不同的SDK
            $pmod = $pay->code;
            $ip = $req->ip();
            return $this->$pmod($oid,$pay,$ip);
        } catch (\Throwable $e) {
            // dd($e);
            return back()->with('message','出错了，一会再试吧！');
        }
    }

    // 余额支付
    private function yue($oid,$pay,$ip = '')
    {
        // 支付事务
        DB::beginTransaction();
        try {
            // 查可用余额是否够用
            $order = Order::findOrFail($oid);
            $user_money = User::where('id',$order->user_id)->value('user_money');
            if ($user_money < $order->total_prices) {
                return back()->with('message','余额不足，请选择其它支付方式！');
            }
            User::where('id',$order->user_id)->sharedLock()->decrement('user_money',$order->total_prices);
            // 消费记录
            if (!OrderApi::updateOrder($order,$paymod = '余额')) {
                DB::rollback();
                return back()->with('message','支付失败，请稍后再试+1！');
            }
            DB::commit();
            return redirect(url('user/orderlist/2'))->with('message','支付成功，等待收货！');
        } catch (\Throwable $e) {
            DB::rollback();
            // Log::info($e);
            return back()->with('message','支付失败，请稍后再试！');
        }
    }

    // 支付宝支付
    private function alipay($oid,$pay,$ip = '')
    {
        $set = json_decode($pay->setting);
        // 手机网站支付NEW
        $gateway = Omnipay::create('Alipay_AopWap');
        $gateway->setSignType('RSA'); //RSA/RSA2
        $gateway->setAppId($set->alipay_appid);
        $gateway->setPrivateKey(config('alipay.privatekey'));
        $gateway->setAlipayPublicKey(config('alipay.publickey'));
        // 即时到账
        /*$gateway = Omnipay::create('Alipay_LegacyExpress');
        $gateway->setSellerEmail($set->alipay_account);
        $gateway->setPartner($set->alipay_partner);
        $gateway->setKey($set->alipay_key); */
        //For MD5 sign type
        // $gateway->setPrivateKey('the_rsa_sign_key'); //For RSA sign type
        // $gateway->setAlipayPublicKey('the_alipay_public_key'); //For RSA sign type
        $gateway->setReturnUrl(config('app.url').'/alipay/return');
        $gateway->setNotifyUrl(config('app.url').'/alipay/gateway');
        // 查订单信息
        $order = Order::findOrFail($oid);
        $request = $gateway->purchase()->setBizContent([
          'out_trade_no' => $order->order_id,
          'subject'      => cache('config')['title'].'订单',
          'total_amount'    => "$order->total_prices",
          'product_code' => 'QUICK_WAP_PAY',
        ]);

        /**
         * @var LegacyExpressPurchaseResponse $response
         */
        $response = $request->send();

        // 下单后跳转到支付页面
        // $redirectUrl = $response->getRedirectUrl();
        //or
        $response->redirect();
    }


    // 微信支付js
    private function weixin($oid,$pay,$ip)
    {
        // 判断是不是微信浏览器
        if (app('com')->is_weixin()) {
            try {
                $payment = app('wechat.payment');
                $openid = session('member')->openid;
                $order = Order::with('good')->findOrFail($oid);
                $result = $payment->order->unify([
                    'body'             => cache('config')['title'].'订单',
                    'detail'           => cache('config')['title'].'订单',
                    'out_trade_no'     => $order->order_id,
                    // 'total_fee'        => 1, // 单位：分
                    'total_fee'        => $order->total_prices * 100, // 单位：分
                    'notify_url'       => config('app.url').'/weixin/return', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
                    'openid'           => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
                ]);
                $js = $payment->jssdk;
                $config = $js->sdkConfig($result['prepay_id']);
                $pos_id = 'cart';
                $title = '订单结算-微信支付';
                return view(cache('config')['theme'].'.pay.wxpay',compact('title','pos_id','config','js','oid','order'));
            } catch (\Throwable $e) {
                // dd($e);
                Storage::disk('log')->prepend('weixin.log',$e->getMessage().date('Y-m-d H:i:s'));
                return back()->with('message','微信支付失败，请稍后再试！');
            }
        }
        else
        {
            try {
                $payment = app('wechat.payment');
                $openid = session('member')->openid;
                $order = Order::with('good')->findOrFail($oid);
                $result = $payment->order->unify([
                    'body'             => cache('config')['title'].'订单',
                    'detail'           => cache('config')['title'].'订单',
                    'out_trade_no'     => $order->order_id,
                    // 'total_fee'        => 1, // 单位：分
                    'total_fee'        => $order->total_prices * 100, // 单位：分
                    'notify_url'       => config('app.url').'/weixin/return', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
                    'openid'           => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
                ]);
                $config = $payment->jssdk->bridgeConfig($result['prepay_id']);
                $pos_id = 'cart';
                $title = '订单结算-微信支付';
                return view(cache('config')['theme'].'.pay.wxpay_jsbridge',compact('title','pos_id','config','oid','order'));
            } catch (\Throwable $e) {
                // dd($e);
                Storage::disk('log')->prepend('weixin.log',$e->getMessage().date('Y-m-d H:i:s'));
                return back()->with('message','微信支付失败，请稍后再试！');
            }
        }
    }
}
