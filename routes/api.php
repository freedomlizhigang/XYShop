<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// vuejs用api接口
Route::group(['namespace'=>'Api'],function(){
    // 取广告
    Route::get('ad/index','AdController@getIndex');
});

// 商城功能
Route::group(['namespace'=>'Common'],function(){
    // 加购物车
    Route::post('good/addcart','AjaxGoodController@postAddcart');
    // 修改购物车
    Route::post('good/changecart','AjaxGoodController@postChangecart');
    // 移除购物车
    Route::post('good/removecart','AjaxGoodController@postRemovecart');
    // 取购物车数量
    Route::post('good/cartnums','AjaxGoodController@postCartnums');
    // 提交订单
    Route::post('good/addorder','AjaxGoodController@postAddorder');
    // 取消订单
    Route::post('good/removeorder','AjaxGoodController@postRemoveOrder');
    // 提交团购
    Route::post('good/addtuan','AjaxTuanController@postAddTuan');
    // 领券
    Route::post('yhq/get','AjaxYhqController@postGet');
    // 删除优惠券
    Route::post('yhq/del','AjaxYhqController@postDel');
    // 领券
    Route::post('yhq/price','AjaxYhqController@postPrice');
});


Route::group(['prefix'=>'auth','namespace'=>'Auth'],function(){
    // 微信登录扫码地址
    Route::get('wxlogincode', 'WxController@getWxlogincode');
    // 微信注册扫码地址
    Route::get('wxregcode', 'WxController@getWxregcode');
});
// 通用功能
Route::group(['prefix'=>'common','namespace'=>'Common'],function(){
    // 取商品子分类
    Route::post('goodcate','AjaxCommonController@postGoodCate');
    // 取品牌
    Route::post('brand','AjaxCommonController@postBrand');
    // 取下级地区
    Route::post('area','AjaxCommonController@postArea');
    // 取社区
    Route::post('community','AjaxCommonController@postCommunity');
    // 编辑器文件上传
    Route::get('ueditor_upload','UploaderController@getUeditorupload');
    Route::post('ueditor_upload','UploaderController@postUeditorupload');
    // 文件上传
    Route::post('upload','UploaderController@postUploadimg');
});

// 会员功能
Route::group(['prefix'=>'user'],function(){
    // 添加收货人信息
    Route::post('ajax_address','Common\AjaxUserController@postAddress');
    // 注册
    Route::post('register','Api\UserController@postRegister');
    // 登陆
    Route::post('login','Api\UserController@postLogin');
});

Route::group(['prefix'=>'user','middleware' => ['jwt']],function(){
    // 注销
    Route::post('logout','Api\UserController@postLogout');
});