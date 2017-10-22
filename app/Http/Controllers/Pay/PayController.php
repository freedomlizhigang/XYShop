<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Common\BaseController;
use App\Models\Common\Pay;
use App\Models\Good\Order;
use App\Models\User\User;
use DB;
use EasyWeChat\Payment\Order as WxOrder;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use QrCode;
use Storage;

class PayController extends BaseController
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
        } catch (\Exception $e) {
            // dd($e);
            return back()->with('message','出错了，一会再试吧！');
        }
    }

    // 余额支付
    private function yue($oid,$pay,$ip = '')
    {
        // 查可用余额是否够用
        $order = Order::findOrFail($oid);
        $user_money = User::where('id',$order->user_id)->value('user_money');
        if ($user_money < $order->total_prices) {
            return back()->with('message','余额不足，请选择其它支付方式！');
        }
        // 支付
        try {
            DB::transaction(function() use($order){
                User::where('id',$order->user_id)->decrement('user_money',$order->total_prices);
                // 消费记录
                $this->updateOrder($order,$paymod = '余额');
            });
            return redirect(url('user/orderlist/2'))->with('message','支付成功，等待收货！');
        } catch (\Exception $e) {
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
        try {
            $wechat = app('wechat');
            $payment = $wechat->payment;
            $openid = session('member')->openid;
            $order = Order::with('good')->findOrFail($oid);
            // $price * 100
            $attributes = [
                'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
                'body'             => cache('config')['title'].'订单',
                'detail'           => cache('config')['title'].'订单',
                'out_trade_no'     => $order->order_id,
                'total_fee'        => $order->total_prices * 100, // 单位：分
                'notify_url'       => config('app.url').'/weixin/return', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid'           => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            ];
            $wxorder = new WxOrder($attributes);
            $result = $payment->prepare($wxorder);
            $prepayId = $result->prepay_id;
            $config = $payment->configForJSSDKPayment($prepayId);
            $js = $wechat->js;
            $pos_id = 'cart';
            $title = '订单结算-微信支付';
            return view($this->theme.'.pay.wxpay',compact('title','pos_id','config','js','oid','order'));
        } catch (\Exception $e) {
            // dd($e);
            Storage::disk('log')->prepend('weixin.log',json_encode($e->getData()).date('Y-m-d H:i:s'));
            return back()->with('message','微信支付失败，请稍后再试！');
        }
    }

    // 老版微信支付，只用支付包
    private function weixin_old($oid,$pay,$ip)
    {
        $set = json_decode($pay->setting);
        // 查订单信息
        $order = Order::findOrFail($oid);
        $gateway = Omnipay::create('WechatPay_Js');
        $gateway->setAppId($set->appid);
        $gateway->setMchId($set->mchid);
        $gateway->setApiKey($set->appkey);
        $gateway->setNotifyUrl(config('app.url').'/weixin/return');

        $order = [
            'body'              => cache('config')['title'].'订单',
            'out_trade_no'      => $order->order_id,
            'total_fee'         => $order->total_prices * 100, //=0.01
            'spbill_create_ip'  => $ip,
            'fee_type'          => 'CNY',
            'openid'            => session('member')->openid,
        ];
        /**
         * @var Omnipay\WechatPay\Message\CreateOrderRequest $request
         * @var Omnipay\WechatPay\Message\CreateOrderResponse $response
         */
        $request  = $gateway->purchase($order);
        $response = $request->send();
        //available methods
        // 如果下单成功，调起支付动作
        if($response->isSuccessful())
        {
            $d = $response->getJsOrderData();
            $info = (object) ['pid'=>2];
            return view($this->theme.'.pay.wxpay',compact('set','d','info'));
        }
        else
        {
            Storage::disk('log')->prepend('weixin.log',json_encode($response->getData()).date('Y-m-d H:i:s'));
            return back()->with('message','微信支付失败，请稍后再试！');
        }
        // $response->getData(); //For debug
        // $response->getAppOrderData(); //For WechatPay_App
        // $response->getJsOrderData(); //For WechatPay_Js
        // $response->getCodeUrl(); //For Native Trade Type
    }


    // 微信支付--扫码
    private function weixin_code($oid,$pay,$ip)
    {
        $set = json_decode($pay->setting);
        $gateway = Omnipay::create('WechatPay_Native');
        $gateway->setAppId($set->appid);
        $gateway->setMchId($set->mchid);
        $gateway->setApiKey($set->appkey);
        $gateway->setNotifyUrl(config('app.url').'/weixin/return');

        $order = [
            'body'              => 'The test order',
            'out_trade_no'      => date('YmdHis').mt_rand(1000, 9999),
            'total_fee'         => 1, //=0.01
            'spbill_create_ip'  => $ip,
            'fee_type'          => 'CNY',
            'openid'            => 'osxIs0mmwpMH5jHrcRFESwSEnW4k',
        ];
        /**
         * @var Omnipay\WechatPay\Message\CreateOrderRequest $request
         * @var Omnipay\WechatPay\Message\CreateOrderResponse $response
         */
        $request  = $gateway->purchase($order);
        $response = $request->send();

        
        //available methods
        // 如果下单成功，调起支付动作
        if($response->isSuccessful())
        {
            $codeurl = $response->getCodeUrl();
            // 移动到新的位置，先创建目录及更新文件名为时间点
            // 生成文件名
            $filename = date('Ymdhis').rand(100, 999);
            $dir = public_path('upload/qrcode/'.date('Ymd').'/');
            if(!is_dir($dir)){
                Storage::disk('log')->makeDirectory('qrcode/'.date('Ymd'));
            }
            $path = $dir.$filename.'.png';
            $src = '/upload/qrcode/'.date('Ymd').'/'.$filename.'.png';
            $ewm = QrCode::format('png')->size(200)->generate($codeurl,$path);
            echo "<h3>扫码支付</h3><img src='".$src."'/>";
        }
        else
        {
            return 0;
            // return back()->with('message','支付失败，请稍后再试');
        }

        // $response->getData(); //For debug
        // $response->getAppOrderData(); //For WechatPay_App
        // $response->getJsOrderData(); //For WechatPay_Js
        // $response->getCodeUrl(); //For Native Trade Type
    }
}
