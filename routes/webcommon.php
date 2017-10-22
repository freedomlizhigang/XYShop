<?php

/*
|--------------------------------------------------------------------------
| WebCommon Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// vue路由用的
// Route::get('/{all}','HomeController@getIndex')->where('all','.*');

// 微信功能
/*Route::group(['prefix'=>'wx'],function(){
    // 接口,注意：一定是 Route::any, 因为微信服务端认证的时候是 GET, 接收用户消息时是 POST ！
    Route::any('index','Wx\WxController@index');
});*/


// 支付回调
Route::group(['namespace' => 'Pay'],function(){
    // 支付宝应用网关,异步回调
    Route::post('alipay/gateway','AlipayController@gateway');
    // 支付宝应用网关,同步回调，同步是get请求
    Route::any('alipay/return','AlipayController@gateway');
    // 微信回调
    Route::post('weixin/return','WxpayController@gateway');
    // 充值的支付回调
    Route::post('alipay/recharge_gateway','AlipayController@recharge_gateway');
    // 支付宝应用网关,同步回调，同步是get请求
    Route::any('alipay/recharge_return','AlipayController@recharge_gateway');
    // 微信回调
    Route::post('weixin/recharge_return','WxpayController@recharge_gateway');
});