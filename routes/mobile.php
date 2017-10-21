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
    // 搜索
    Route::get('search','HomeController@getSearch');
    // 购物车
    Route::get('cart','OrderController@getCart');
    // 结算页面
    Route::post('createorder','OrderController@postCreateorder');
    Route::get('createorder','OrderController@getCreateorder');
    // 选择支付方式页面
    Route::get('pay/{oid}','OrderController@getPay');
    // 用户中心
    Route::get('center','UserController@getCenter');
    // 用户订单
    Route::get('user/orderlist/{sid?}','UserController@getOrderlist');
    // 个人信息
    Route::get('userinfo','UserController@getUserinfo');
    Route::post('userinfo','UserController@postUserinfo');
    // 改密码
    Route::get('passwd','UserController@getPasswd');
    Route::post('passwd','UserController@postPasswd');
    // 消费记录
    Route::get('consume','UserController@getConsume');
    // 优惠券
    Route::get('user/coupon','UserController@getCoupon');
    // 地址
    Route::get('user/address','UserAddressController@getAddress');
    Route::get('user/address/add','UserAddressController@getAddressAdd');
    Route::post('user/address/add','UserAddressController@postAddressAdd');
    Route::get('user/address/edit/{id}','UserAddressController@getAddressEdit');
    Route::post('user/address/edit/{id}','UserAddressController@postAddressEdit');
    Route::get('user/address/del/{id}','UserAddressController@getAddressDel');
});
// 发起支付
Route::get('order/pay/{oid}','Pay\PayController@getTopay')->middleware('member');
// 用户
Route::group(['namespace' => 'Mobile'],function(){
    // 登陆
    Route::get('login','UserController@getLogin');
    // 微信登陆页面
    Route::get('wxlogin','UserController@getWxLogin');
});
