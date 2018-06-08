<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Models\Common\Pay;
use App\Models\Good\Order;
use App\Models\User\User;
use DB;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use QrCode;
use Storage;

class PayController extends BaseController
{

    // 取订单可以使用的支付方式
    public function list($oid)
    {
    	$info = (object) ['pid'=>3];
    	$order = Order::findOrFail($oid);
    	$paylist = Pay::where('status',1)->where('paystatus',1)->orderBy('id','asc')->get();
    	return view($this->theme.'.pay.paylist',compact('info','order','paylist'));
    }
    // 真正的支付
    public function pay(Request $req,$oid)
    {
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
    	/*if ($res) {
    		return redirect('user/order/1')->with('message','支付成功！');
    	}
    	else
    	{
    		return back()->with('message','支付失败，稍后再试！');
    	}*/
    }

    // 余额支付
    private function yue($oid,$pay,$ip = '')
    {
        // 支付事务
        DB::beginTransaction();
        try {
        	// 查可用余额是否够用
        	$order = Order::findOrFail($oid);
        	$user_money = User::where('id',$order->user_id)->sharedLock()->value('user_money');
        	if ($user_money < $order->total_prices) {
        		return back()->with('message','余额不足，请选择其它支付方式！');
        	}
			User::where('id',$order->user_id)->decrement('user_money',$order->total_prices);
			// 库存计算
			if (!$this->updateOrder($order,$paymod = '余额')) {
                DB::rollback();
                return redirect('user/order/1')->with('message','支付失败，请稍后再试！');
            }
            DB::commit();
            return redirect('user/order/2')->with('message','支付成功！');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect('user/order/1')->with('message','支付失败，请稍后再试！');
    	}
    }

    // 银联
    public function unionpay($oid = '',$pay ='',$ip = '')
    {
    	$gateway    = Omnipay::create('UnionPay_Express');
    	// 测试环境参数
    	// $config = ['merId'=>'700000000000001','certPath'=>storage_path('pay/unionpay/700000000000001_acp.pfx'),'certPassword'=>'000000','returnUrl'=>config('app.url').'/union/success','notifyUrl'=>config('app.url').'/union/return'];
    	// 正式环境参数
        $set = json_decode($pay->setting);
        $config = ['merId'=>$set->mchid,'certPath'=>storage_path('pay/unionpay/'.$set->certname.'.pfx'),'certPassword'=>$set->pwd,'returnUrl'=>config('app.url').'/union/success?oid='.$oid,'notifyUrl'=>config('app.url').'/union/return'];
    	$gateway->setEnvironment('production'); // 环境设置，默认是测试环境，必须手动修改成正式的
    	$gateway->setMerId($config['merId']);
    	$gateway->setCertPath($config['certPath']); // .pfx file
    	$gateway->setCertPassword($config['certPassword']);
    	$gateway->setReturnUrl($config['returnUrl']);
    	$gateway->setNotifyUrl($config['notifyUrl']);

        // 查订单信息
        $order = Order::findOrFail($oid);

    	$order = [
    	    // 'orderId'   => date('YmdHis'), //Your order ID $order->order_id
            'orderId'   => $order->order_id, //Your order ID
    	    'txnTime'   => date('YmdHis'), //Should be format 'YmdHis'
    	    'orderDesc' => '吉鲜蜂订单', //Order Title
    	    'txnAmt'    => $order->total_prices * 100, //Order Total Fee
    	];
    	$response = $gateway->purchase($order)->send();
    	echo $response->getRedirectHtml(); //For PC/Wap
    	// return $res;
    	// $response->getTradeNo(); //For APP
    }
    public function unionSuccess(Request $req)
    {
        // 查出订单信息来，并展示成功页面
        $oid = $req->oid;
        $info = Order::findOrFail($oid);
        $info->pid = 4;
        return view('user.union',compact('info'));
    }
    public function unionNotify(Request $req)
    {
        DB::beginTransaction();
        try {
    		// 写入到日日志里方便查看
            if ($req->respCode == 00) {
                // 库存计算
                $oid = $req->orderId;
                $order = Order::where('order_id',$oid)->first();
                if (!$this->updateOrder($order,$paymod = '银联')) {
                    DB::rollback();
                    Storage::disk('log')->prepend('unionpay.log',json_encode($req->all()).date('Y-m-d H:i:s'));
                    exit('fail');
                }
                DB::commit();
                exit('200');
            }else{
                DB::rollback();
                exit('fail');
            }
        } catch (\Throwable $e) {
            DB::rollback();
            Storage::disk('log')->prepend('unionpay.log',json_encode($req->all()).date('Y-m-d H:i:s'));
            Storage::disk('log')->prepend('unionpay.log',json_encode($e->getMessage()).date('Y-m-d H:i:s'));
            exit('fail');
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
		  'subject'      => '吉鲜蜂订单',
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
    	$set = json_decode($pay->setting);
    	// 查订单信息
    	$order = Order::findOrFail($oid);
    	$gateway = Omnipay::create('WechatPay_Js');
		$gateway->setAppId($set->appid);
		$gateway->setMchId($set->mchid);
		$gateway->setApiKey($set->appkey);
		$gateway->setNotifyUrl(config('app.url').'/weixin/return');

		$order = [
		    'body'              => '吉鲜蜂订单',
		    'out_trade_no'      => $order->order_id,
		    'total_fee'         => $order->total_prices * 100, //=0.01
		    'spbill_create_ip'  => $ip,
		    'fee_type'          => 'CNY',
		    'openid'			=> session('member')->openid,
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


    // 微信支付
    private function weixin_js($oid,$pay,$ip)
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
		    'openid'			=> 'osxIs0mmwpMH5jHrcRFESwSEnW4k',
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
