<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // 支付
        'alipay/gateway',
        'alipay/return',
        'weixin/return',
        'alipay/recharge_gateway',
        'alipay/recharge_return',
        'weixin/recharge_return',
        'api/user/perfect',
        // 'union/return',
        // 'union/success',
        // 微信
        'wx/*',
        'oauth/*',
        // 订单结算临时数据
        'shop/orderinfo',
        'createorder',
    	// 后台文件上传
        'console/attr/uploadimg',
        // 取规格
        'console/good/goodspecinput',
        'console/good/goodspecstr',
        'console/goodspec/*',
        'console/goodspecitem/*',
    ];
}
