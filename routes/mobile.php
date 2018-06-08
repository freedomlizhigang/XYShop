<?php

/*
|--------------------------------------------------------------------------
| Home Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('lock','Mobile\TestController@getLock');
Route::get('lockget','Mobile\TestController@getLockget');

// 手机版
Route::group(['namespace' => 'Mobile','middleware'=>'member'],function(){
    // 首页
    Route::get('/','HomeController@getIndex');
    // 分类页面
    Route::get('catelist/{id?}','HomeController@getCatelist');
    // 商品列表
    Route::get('list/{id?}','HomeController@getList');
    // 商品页面
    Route::get('good/{id}','HomeController@getGood');
    // 抢购商品
    Route::get('timetobuy/{id}','TimetobuyController@getGood');
    // 团购商品
    Route::get('tuan/{id}','TuanController@getGood');
    // 提交订单--活动的
    Route::get('editorder/{oid}','OrderController@getEditorder');
    // 搜索
    Route::get('search','HomeController@getSearch');
    // 活动列表
    Route::get('hot','HotController@getHot');
    Route::get('hot/{id}','HotController@getHotList');
    // 活动商品
    Route::get('hotgood/{id}','HotController@getGood');
    // 购物车
    Route::get('cart','OrderController@getCart');
    // 结算页面
    Route::get('createorder','OrderController@getCreateorder');
    Route::post('createorder','OrderController@postCreateorder');
    // 选择支付方式页面
    Route::get('pay/{oid}','OrderController@getPay');
    // 以下是用户功能
    // 用户中心
    Route::get('center','UserController@getCenter');
    // 用户订单
    Route::get('user/orderlist/{sid?}','UserOrderController@getOrderlist');
    Route::get('user/orderinfo/{id}','UserOrderController@getOrderInfo');
    // 退换
    Route::get('user/returngood/{ogid}','UserOrderController@getReturnGood');
    Route::post('user/returngood/{ogid}','UserOrderController@postReturnGood');
    // 个人信息
    Route::get('userinfo','UserController@getUserinfo');
    Route::post('userinfo','UserController@postUserinfo');
    // 改密码
    Route::get('passwd','UserController@getPasswd');
    Route::post('passwd','UserController@postPasswd');
    // 消费记录
    Route::get('consume','UserController@getConsume');
    // 充值
    Route::get('user/recharge','RechargeController@getRecharge');
    Route::post('user/recharge','RechargeController@postRecharge');
    // 优惠券
    Route::get('user/coupon','UserController@getCoupon');
    // 签到
    Route::get('user/signin','SignController@getSignin');
    // 地址
    Route::get('user/address','UserAddressController@getAddress');
    Route::get('user/address/add','UserAddressController@getAddressAdd');
    Route::post('user/address/add','UserAddressController@postAddressAdd');
    Route::get('user/address/edit/{id}','UserAddressController@getAddressEdit');
    Route::post('user/address/edit/{id}','UserAddressController@postAddressEdit');
    Route::get('user/address/del/{id}','UserAddressController@getAddressDel');
    // 生成分销链接
    Route::get('user/distribution/shareurl','DistributionController@getShareurl');
    // 分成记录
    Route::get('user/distribution/logs','DistributionController@getLogs');
    // 充值卡激活
    Route::get('user/card','UserController@getCard');
    Route::post('user/card','UserController@postCard');
});
// 发起支付
Route::get('order/pay/{oid}','Pay\PayController@getTopay')->middleware('member');
// 用户
Route::group(['namespace' => 'Mobile'],function(){
    // 登陆
    Route::get('login','LoginController@getLogin');
    Route::post('login','LoginController@postLogin');
    // 微信登陆页面
    Route::get('wxlogin','LoginController@getWxLogin');
});
// 退出登录
Route::get('logout','Mobile\LoginController@getLogout');
