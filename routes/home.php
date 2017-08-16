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

Route::get('/data','DataController@index');


// Home PC版
Route::group(['namespace' => 'Home'],function(){
    // 首页
    Route::get('/','HomeController@getIndex');
    // 分类页面
    Route::get('list/{id}','HomeController@getList');
    // 商品页面
    Route::get('good/{id}','HomeController@getGood');
    // 搜索
    Route::get('/search','HomeController@getSearch');
    // 栏目
    Route::get('/cate/{url}','HomeController@getCate');
    // 文章
    Route::get('/post/{url}','HomeController@getPost');
});
// 会员功能
Route::group(['prefix'=>'user','namespace' => 'Home'],function(){
    // 注册
    Route::get('register','UserController@getRegister');
    Route::post('register','UserController@postRegister');
    // 登陆
    Route::get('login','UserController@getLogin');
    Route::post('login','UserController@postLogin');
    // 退出登陆
    Route::get('logout','UserController@getLogout');
    // 忘记密码
    Route::get('forpwd','UserController@getForpwd');
    Route::post('forpwd','UserController@postForpwd');
    Route::get('forpwd2','UserController@getForpwd2');
    Route::post('forpwd2','UserController@postForpwd2');
});
// 会员功能
Route::group(['prefix'=>'user','middleware' => ['member'],'namespace' => 'Home'],function(){
    // 消费记录
    Route::get('consume','UserCenterController@getConsume');
    // 充值卡激活
    Route::get('card','UserCenterController@getCard');
    Route::post('card','UserCenterController@postCard');
    // 退货
    Route::get('returngood','UserCenterController@getReturngood');
    Route::get('order/tui/{id}/{gid}','ShopController@getTui');
    Route::post('order/tui/{id}/{gid}','ShopController@postTui');
    // 订单列表
    Route::get('order/{status}','ShopController@getOrder');
    // 优惠券列表
    Route::get('yhq','YhqController@getList');
    // 地址管理
    Route::get('address','UserCenterController@getAddress');
    Route::get('address/add','UserCenterController@getAddressAdd');
    Route::post('address/add','UserCenterController@postAddressAdd');
    Route::get('address/edit/{id}','UserCenterController@getAddressEdit');
    Route::post('address/edit/{id}','UserCenterController@postAddressEdit');
    Route::get('address/del/{id}','UserCenterController@getAddressDel');
    // 修改个人信息
    Route::get('info','UserCenterController@getInfo');
    Route::post('info','UserCenterController@postInfo');
    Route::get('pwd','UserCenterController@getPwd');
    Route::post('pwd','UserCenterController@postPwd');
    // 会员中心
    Route::get('center','UserCenterController@getCenter');
});
// 商城功能
Route::group(['prefix'=>'shop','namespace' => 'Home'],function(){
    // 活动
    Route::get('hd/index','HuodongController@getIndex');
    Route::get('hd/list/{id}','HuodongController@getList');
    // 优惠券
    Route::get('yhq/index','YhqController@getIndex');
    // 分类列表
    Route::get('goodlist/{id}','ShopController@getGoodlist');
    // 分类
    Route::get('goodcate/{id?}','ShopController@getGoodcate');
    // 商品
    Route::get('good/{id}/{format?}','ShopController@getGood');
    // 取购物车数量
    Route::get('cartnums','ShopController@getCartnums');
    // 团购商品
    Route::get('tuan/{tid}/{gid}','TuanController@getGood');
});
// 商城功能-登陆后的
Route::group(['prefix'=>'shop','middleware' => ['member'],'namespace' => 'Home'],function(){
    // 购物车
    Route::get('cart','ShopController@getCart');
    // 订单结算页
    Route::get('orderinfo','ShopController@getOrderinfo');
    // 提交订单结算页
    Route::post('orderinfo','ShopController@postOrderinfo');
    // 团购下单
    Route::get('tuan/addorder','TuanController@getAddorder');
    // 订单评价
    Route::get('order/ship/{oid}','ShopController@getShip');
    // 订单评价
    Route::get('good/comment/{oid}/{gid}','ShopController@getComment');
    Route::post('good/comment/{oid}/{gid}','ShopController@postComment');
    // 提交订单完成，付款
    Route::get('pay/{oid}','ShopController@getOrderpay');
    // 支付
    Route::get('order/pay/{oid}','PayController@pay');
    // Route::post('order/pay/{oid}','PayController@pay');
});


// 支付回调
Route::group([],function(){
    // 支付宝应用网关,异步回调
    Route::post('alipay/gateway','Pay\AlipayController@gateway');
    // 支付宝应用网关,同步回调
    Route::post('alipay/return','Pay\AlipayController@gateway');
    // 微信回调
    Route::post('weixin/return','Pay\WxpayController@gateway');
    // 银联回调接口
    // Route::get('/pay/unionpay','PayController@unionpay');
    Route::post('union/return','Home\PayController@unionNotify');
    Route::any('union/success','Home\PayController@unionSuccess');
});

// 社会化登录认证
Route::group(['prefix' => 'oauth','namespace'=>'Auth'],function(){
    // 真正的微信登录地址
    Route::get('wxlogin', 'WxController@getWxlogin');
    // 微信登陆回调地址
    Route::get('wxlogincallback', 'WxController@getLogincallback');
    // 真正的微信注册地址
    Route::get('wxreg', 'WxController@getWxreg');
    // 微信注册回调地址
    Route::get('wxregcallback', 'WxController@getRegcallback');
    // 轮询微信登录扫码地址
    Route::get('wxscancode', 'WxController@getWxscancode');
});

// 微信功能
Route::group(['prefix'=>'wx'],function(){
    // 接口,注意：一定是 Route::any, 因为微信服务端认证的时候是 GET, 接收用户消息时是 POST ！
    Route::any('index','Wx\WxController@index');
});
