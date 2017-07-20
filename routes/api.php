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

// 商城功能
Route::group(['prefix'=>'common','namespace'=>'Common'],function(){
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




// 会员功能
Route::group(['prefix'=>'user'],function(){
    // 注册
    Route::post('register','Api\UserController@postRegister');
    // 登陆
    Route::post('login','Api\UserController@postLogin');
});

Route::group(['prefix'=>'user','middleware' => ['jwt']],function(){
    // 注销
    Route::post('logout','Api\UserController@postLogout');
});