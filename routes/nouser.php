<?php
/*
* 暂时没用到的
*/


// 社会化登录认证--暂时无用
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
