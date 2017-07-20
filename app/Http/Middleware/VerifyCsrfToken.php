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
        'union/return',
        'union/success',
        // 微信
        'wx/*',
        'oauth/*',
    	// 后台文件上传
        'console/attr/uploadimg',
        // 取规格
        'console/good/goodspecinput',
    ];
}
