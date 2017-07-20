<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/vue','VueController@index');
// Route::get('/import','VueController@database');
// Route::get('/area','VueController@area');


// Home PC版
Route::group(['namespace' => 'Home'],function(){
    Route::get('/','HomeController@index');
    Route::get('/search','HomeController@getSearch');
    Route::get('/cate/{url}','HomeController@getCate');
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
    // 直接购买
    Route::get('firstorder','ShopController@getFirstOrder');
    // 团购下单
    Route::get('tuan/addorder','TuanController@getAddorder');
    // 订单评价
    Route::get('order/ship/{oid}','ShopController@getShip');
    // 订单评价
    Route::get('good/comment/{oid}/{gid}','ShopController@getComment');
    Route::post('good/comment/{oid}/{gid}','ShopController@postComment');
    // 购物车
    Route::get('cart','ShopController@getCart');
    // 提交订单
    Route::get('addorder/{oid}','ShopController@getAddorder');
    // 支付
    Route::get('order/pay/{oid}','PayController@list');
    Route::post('order/pay/{oid}','PayController@pay');
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
Route::group(['prefix' => 'oauth'],function(){
    // 微信登录扫码地址
    Route::get('wxlogin', 'Auth\WxController@login');
    // 轮询地址
    Route::get('wxislogin', 'Auth\WxController@islogin');
    // 真正的微信登录地址
    Route::get('wx', 'Auth\WxController@wx');
    // 微信回调地址
    Route::get('wx/callback', 'Auth\WxController@callback');
});

// 微信功能
Route::group(['prefix'=>'wx'],function(){
    // 接口,注意：一定是 Route::any, 因为微信服务端认证的时候是 GET, 接收用户消息时是 POST ！
    Route::any('index','Wx\WxController@index');
});



// 后台路由
Route::group(['prefix'=>'console','namespace' => 'Admin'],function(){
    // 后台管理不用其它，只用登陆，退出
    // Route::auth();
    Route::get('login', 'PublicController@getLogin');
    Route::post('login', 'PublicController@postLogin');
    // 退出登陆
    Route::get('logout', 'PublicController@getLogout');
});

Route::group(['prefix'=>'console','middleware' => ['rbac'],'namespace' => 'Admin'],function(){
    // 商铺
    Route::get('shop/index', 'Shop\ShopController@getIndex');
    Route::get('shop/add', 'Shop\ShopController@getAdd');
    Route::post('shop/add', 'Shop\ShopController@postAdd');
    Route::get('shop/edit/{id}', 'Shop\ShopController@getEdit');
    Route::post('shop/edit/{id}', 'Shop\ShopController@postEdit');
    Route::get('shop/del/{id}', 'Shop\ShopController@getDel');
    Route::get('shop/status/{id}', 'Shop\ShopController@getStatus');
    Route::post('shop/status/{id}', 'Shop\ShopController@postStatus');
    Route::get('shop/active/{id}/{active}', 'Shop\ShopController@getActive');
    Route::get('shop/pos/{id}/{pos}', 'Shop\ShopController@getPos');
    // 商铺权限菜单
    Route::get('shopmenu/index', 'Shop\ShopMenuController@getIndex');
    Route::get('shopmenu/add/{id?}', 'Shop\ShopMenuController@getAdd');
    Route::post('shopmenu/add/{id?}', 'Shop\ShopMenuController@postAdd');
    Route::get('shopmenu/edit/{id}', 'Shop\ShopMenuController@getEdit');
    Route::post('shopmenu/edit/{id}', 'Shop\ShopMenuController@postEdit');
    Route::get('shopmenu/del/{id}', 'Shop\ShopMenuController@getDel');
    // 商铺分类
    Route::get('shopcate/index', 'Shop\ShopCateController@getIndex');
    Route::get('shopcate/cache', 'Shop\ShopCateController@getCache');
    Route::get('shopcate/add/{id?}', 'Shop\ShopCateController@getAdd');
    Route::post('shopcate/add/{id?}', 'Shop\ShopCateController@postAdd');
    Route::get('shopcate/edit/{id}', 'Shop\ShopCateController@getEdit');
    Route::post('shopcate/edit/{id}', 'Shop\ShopCateController@postEdit');
    Route::get('shopcate/del/{id}', 'Shop\ShopCateController@getDel');
    Route::post('shopcate/sort', 'Shop\ShopCateController@postSort');
    // 广告位
    Route::get('adpos/index', 'Common\AdposController@getIndex');
    Route::get('adpos/add', 'Common\AdposController@getAdd');
    Route::post('adpos/add', 'Common\AdposController@postAdd');
    Route::get('adpos/edit/{id}', 'Common\AdposController@getEdit');
    Route::post('adpos/edit/{id}', 'Common\AdposController@postEdit');
    Route::get('adpos/del/{id}', 'Common\AdposController@getDel');
    // 品牌
    Route::get('brand/index', 'Good\BrandController@getIndex');
    Route::get('brand/add', 'Good\BrandController@getAdd');
    Route::post('brand/add', 'Good\BrandController@postAdd');
    Route::get('brand/edit/{id}', 'Good\BrandController@getEdit');
    Route::post('brand/edit/{id}', 'Good\BrandController@postEdit');
    Route::get('brand/del/{id}', 'Good\BrandController@getDel');
    // 社区
    Route::get('community/index', 'Common\CommunityController@getIndex');
    Route::get('community/add', 'Common\CommunityController@getAdd');
    Route::post('community/add', 'Common\CommunityController@postAdd');
    Route::get('community/edit/{id}', 'Common\CommunityController@getEdit');
    Route::post('community/edit/{id}', 'Common\CommunityController@postEdit');
    Route::get('community/del/{id}', 'Common\CommunityController@getDel');
    // 省市区域
    Route::get('area/index/{pid?}', 'Common\AreaController@getIndex');
    Route::get('area/add/{pid}', 'Common\AreaController@getAdd');
    Route::post('area/add/{pid}', 'Common\AreaController@postAdd');
    Route::get('area/edit/{id}', 'Common\AreaController@getEdit');
    Route::post('area/edit/{id}', 'Common\AreaController@postEdit');
    Route::get('area/del/{id}', 'Common\AreaController@getDel');
    Route::get('area/get/{pid}', 'Common\AreaController@getGet');
    // 会员卡
    Route::get('card/index', 'User\CardController@getIndex');
    Route::get('card/excel', 'User\CardController@getCardExcel');
    Route::get('card/add', 'User\CardController@getAdd');
    Route::post('card/add', 'User\CardController@postAdd');
    Route::post('card/del', 'User\CardController@postAlldel');
    // 退货管理
    Route::get('returngood/index', 'Common\RetrunGoodController@getIndex');
    Route::get('returngood/excel', 'Common\RetrunGoodController@getExcel');
    Route::get('returngood/status/{id}', 'Common\RetrunGoodController@getStatus');
    Route::post('returngood/status/{id}', 'Common\RetrunGoodController@postStatus');
    // 今日消费情况
    Route::get('index/consume', 'IndexController@getConsume');
    Route::get('index/excel_consume', 'IndexController@getExcelConsume');
    // 导出库房用表
    Route::get('index/excel_store', 'IndexController@getExcelStore');
    // 导出出货量
    Route::get('index/excel_goods', 'IndexController@getExcelGoods');
    // 导出待打印订单
    Route::get('index/excel_order', 'IndexController@getExcelOrders');
    // 自提点管理
    Route::get('ziti/index', 'Common\ZitiController@getIndex');
    Route::get('ziti/add', 'Common\ZitiController@getAdd');
    Route::post('ziti/add', 'Common\ZitiController@postAdd');
    Route::get('ziti/edit/{id}', 'Common\ZitiController@getEdit');
    Route::post('ziti/edit/{id}', 'Common\ZitiController@postEdit');
    Route::get('ziti/del/{id}', 'Common\ZitiController@getDel');
    Route::post('ziti/sort', 'Common\ZitiController@postSort');
    Route::post('ziti/alldel', 'Common\ZitiController@postAlldel');
    // 广告管理
    Route::get('ad/index', 'Common\AdController@getIndex');
    Route::get('ad/add', 'Common\AdController@getAdd');
    Route::post('ad/add', 'Common\AdController@postAdd');
    Route::get('ad/edit/{id}', 'Common\AdController@getEdit');
    Route::post('ad/edit/{id}', 'Common\AdController@postEdit');
    Route::get('ad/del/{id}', 'Common\AdController@getDel');
    Route::post('ad/sort', 'Common\AdController@postSort');
    Route::post('ad/alldel', 'Common\AdController@postAlldel');
    // 团购管理
    Route::get('tuan/index', 'Good\TuanController@getIndex');
    Route::get('tuan/add/{id}', 'Good\TuanController@getAdd');
    Route::post('tuan/add/{id}', 'Good\TuanController@postAdd');
    Route::get('tuan/edit/{id}', 'Good\TuanController@getEdit');
    Route::post('tuan/edit/{id}', 'Good\TuanController@postEdit');
    Route::get('tuan/del/{id}', 'Good\TuanController@getDel');
    Route::post('tuan/sort', 'Good\TuanController@postSort');
    Route::post('tuan/alldel', 'Good\TuanController@postAlldel');
    // 满赠管理
    Route::get('manzeng/index', 'Good\ManzengController@getIndex');
    Route::get('manzeng/add/{id}', 'Good\ManzengController@getAdd');
    Route::post('manzeng/add/{id}', 'Good\ManzengController@postAdd');
    Route::get('manzeng/edit/{id}', 'Good\ManzengController@getEdit');
    Route::post('manzeng/edit/{id}', 'Good\ManzengController@postEdit');
    Route::get('manzeng/del/{id}', 'Good\ManzengController@getDel');
    Route::post('manzeng/sort', 'Good\ManzengController@postSort');
    Route::post('manzeng/alldel', 'Good\ManzengController@postAlldel');
    // 优惠券管理
    Route::get('youhuiquan/index', 'Common\YouhuiquanController@getIndex');
    Route::get('youhuiquan/add', 'Common\YouhuiquanController@getAdd');
    Route::post('youhuiquan/add', 'Common\YouhuiquanController@postAdd');
    Route::get('youhuiquan/edit/{id}', 'Common\YouhuiquanController@getEdit');
    Route::post('youhuiquan/edit/{id}', 'Common\YouhuiquanController@postEdit');
    Route::get('youhuiquan/del/{id}', 'Common\YouhuiquanController@getDel');
    Route::post('youhuiquan/sort', 'Common\YouhuiquanController@postSort');
    Route::post('youhuiquan/alldel', 'Common\YouhuiquanController@postAlldel');
    // 活动管理
    Route::get('huodong/index', 'Good\HuodongController@getIndex');
    Route::get('huodong/add', 'Good\HuodongController@getAdd');
    Route::post('huodong/add', 'Good\HuodongController@postAdd');
    Route::get('huodong/edit/{id}', 'Good\HuodongController@getEdit');
    Route::post('huodong/edit/{id}', 'Good\HuodongController@postEdit');
    Route::get('huodong/del/{id}', 'Good\HuodongController@getDel');
    Route::get('huodong/good/{gids?}', 'Good\HuodongController@getGood');
    Route::post('huodong/good/{gids?}', 'Good\HuodongController@postGood');
    Route::get('huodong/goodlist/{id}', 'Good\HuodongController@getGoodlist');
    Route::get('huodong/rmgood/{id}/{gid}', 'Good\HuodongController@getRmgood');
    Route::post('huodong/sort', 'Good\HuodongController@postSort');
    Route::post('huodong/alldel', 'Good\HuodongController@postAlldel');
    // 订单管理
    Route::get('order/index', 'Common\OrderController@index');
    Route::get('order/del/{id}', 'Common\OrderController@getDel');
    Route::get('order/ship/{id}', 'Common\OrderController@getShip');
    Route::post('order/ship/{id}', 'Common\OrderController@postShip');
    Route::get('order/print/{id}', 'Common\OrderController@getPrint');
    // Route::get('order/tui/{id}', 'Common\OrderController@getTui');
    Route::get('order/ziti/{id}', 'Common\OrderController@getZiti');
    // 批量自提、发货、关闭
    Route::post('order/allship', 'Common\OrderController@postAllShip');
    Route::post('order/allziti', 'Common\OrderController@postAllZiti');
    Route::post('order/allclose', 'Common\OrderController@postAllDel');
    // 支付配置
    Route::get('pay/index', 'Common\PayController@getIndex');
    Route::get('pay/edit/{id}', 'Common\PayController@getEdit');
    Route::post('pay/edit/{id}', 'Common\PayController@postEdit');
    // 商品分类
    Route::get('goodcate/index', 'Good\GoodCateController@getIndex');
    Route::get('goodcate/cache', 'Good\GoodCateController@getCache');
    Route::get('goodcate/add/{id?}', 'Good\GoodCateController@getAdd');
    Route::post('goodcate/add/{id?}', 'Good\GoodCateController@postAdd');
    Route::get('goodcate/edit/{id?}', 'Good\GoodCateController@getEdit');
    Route::post('goodcate/edit/{id?}', 'Good\GoodCateController@postEdit');
    Route::get('goodcate/del/{id?}', 'Good\GoodCateController@getDel');
    Route::get('goodcate/attr/{id?}', 'Good\GoodCateController@getAttr');
    Route::post('goodcate/attr/{id?}', 'Good\GoodCateController@postAttr');
    Route::post('goodcate/sort', 'Good\GoodCateController@postSort');
    // 商品规格
    Route::get('goodspec/index/{pid?}', 'Good\GoodSpecController@getIndex');
    Route::get('goodspec/add', 'Good\GoodSpecController@getAdd');
    Route::post('goodspec/add', 'Good\GoodSpecController@postAdd');
    Route::get('goodspec/edit/{id?}', 'Good\GoodSpecController@getEdit');
    Route::post('goodspec/edit/{id?}', 'Good\GoodSpecController@postEdit');
    Route::get('goodspec/del/{id?}', 'Good\GoodSpecController@getDel');
    // 商品属性
    Route::get('goodattr/index/{pid?}', 'Good\GoodAttrController@getIndex');
    Route::get('goodattr/add', 'Good\GoodAttrController@getAdd');
    Route::post('goodattr/add', 'Good\GoodAttrController@postAdd');
    Route::get('goodattr/edit/{id?}', 'Good\GoodAttrController@getEdit');
    Route::post('goodattr/edit/{id?}', 'Good\GoodAttrController@postEdit');
    Route::get('goodattr/del/{id?}', 'Good\GoodAttrController@getDel');
    // 商品
    Route::get('good/index', 'Good\GoodController@getIndex');
    Route::get('good/nostore', 'Good\GoodController@getNostore');
    Route::get('good/add/{id?}', 'Good\GoodController@getAdd');
    Route::post('good/add/{id?}', 'Good\GoodController@postAdd');
    Route::get('good/edit/{id?}', 'Good\GoodController@getEdit');
    Route::post('good/edit/{id?}', 'Good\GoodController@postEdit');
    Route::get('good/del/{id}/{status}', 'Good\GoodController@getDel');
    Route::post('good/sort', 'Good\GoodController@postSort');
    Route::post('good/alldel', 'Good\GoodController@postAlldel');
    // 商品批量上下架
    Route::post('good/allstatus', 'Good\GoodController@postAllStatus');
    Route::post('good/allcate', 'Good\GoodController@postAllCate');
    // 取商品分类及规格
    Route::get('good/goodattr', 'Good\GoodController@getGoodAttr');
    Route::get('good/goodspec', 'Good\GoodController@getGoodSpec');
    Route::post('good/goodspecinput', 'Good\GoodController@postGoodSpecInput');    
    // Index
    Route::get('index/index', 'IndexController@getIndex');
    Route::get('index/main', 'IndexController@getMain');
    Route::get('index/left/{id}', 'IndexController@getLeft');
    Route::get('index/cache', 'IndexController@getCache');
    // 系统配置
    Route::get('config/index', 'ConfigController@index');
    Route::post('config/index', 'ConfigController@postIndex');
    // admin
    Route::get('admin/index', 'AdminController@getIndex');
    Route::get('admin/add', 'AdminController@getAdd');
    Route::post('admin/add', 'AdminController@postAdd');
    Route::post('admin/edit/{id?}', 'AdminController@postEdit');
    Route::get('admin/edit/{id?}', 'AdminController@getEdit');
    Route::get('admin/pwd/{id?}', 'AdminController@getPwd');
    Route::post('admin/pwd/{id?}', 'AdminController@postPwd');
    Route::get('admin/del/{id?}', 'AdminController@getDel');
    Route::get('admin/myedit', 'AdminController@getMyedit');
    Route::post('admin/myedit', 'AdminController@postMyedit');
    Route::get('admin/mypwd', 'AdminController@getMypwd');
    Route::post('admin/mypwd', 'AdminController@postMypwd');
    // role
    Route::get('role/index', 'RoleController@getIndex');
    Route::get('role/add', 'RoleController@getAdd');
    Route::post('role/add', 'RoleController@postAdd');
    Route::get('role/edit/{id?}', 'RoleController@getEdit');
    Route::post('role/edit/{id?}', 'RoleController@postEdit');
    Route::get('role/del/{id?}', 'RoleController@getDel');
    Route::get('role/priv/{id?}', 'RoleController@getPriv');
    Route::post('role/priv/{id?}', 'RoleController@postPriv');
    // 部门
    Route::get('section/index', 'SectionController@getIndex');
    Route::get('section/add', 'SectionController@getAdd');
    Route::post('section/add', 'SectionController@postAdd');
    Route::get('section/edit/{id}', 'SectionController@getEdit');
    Route::post('section/edit/{id}', 'SectionController@postEdit');
    Route::get('section/del/{id}', 'SectionController@getDel');
    // menu
    Route::get('menu/index', 'MenuController@getIndex');
    Route::get('menu/add/{id?}', 'MenuController@getAdd');
    Route::post('menu/add/{id?}', 'MenuController@postAdd');
    Route::get('menu/edit/{id}', 'MenuController@getEdit');
    Route::post('menu/edit/{id}', 'MenuController@postEdit');
    Route::get('menu/del/{id}', 'MenuController@getDel');
    // log
    Route::get('log/index', 'LogController@getIndex');
    Route::get('log/del', 'LogController@getDel');
    // cate
    Route::get('cate/index', 'CateController@getIndex');
    Route::get('cate/cache', 'CateController@getCache');
    Route::get('cate/add/{id?}', 'CateController@getAdd');
    Route::post('cate/add/{id?}', 'CateController@postAdd');
    Route::get('cate/edit/{id?}', 'CateController@getEdit');
    Route::post('cate/edit/{id?}', 'CateController@postEdit');
    Route::get('cate/del/{id?}', 'CateController@getDel');
    // attr
    Route::get('attr/index', 'AttrController@getIndex');
    Route::get('attr/delfile/{id?}', 'AttrController@getDelfile');
    Route::post('attr/uploadimg', 'AttrController@postUploadimg');
    // art
    Route::get('art/index', 'ArtController@getIndex');
    Route::get('art/add/{id?}', 'ArtController@getAdd');
    Route::post('art/add/{id?}', 'ArtController@postAdd');
    Route::get('art/edit/{id}', 'ArtController@getEdit');
    Route::post('art/edit/{id}', 'ArtController@postEdit');
    Route::get('art/del/{id}', 'ArtController@getDel');
    Route::get('art/show/{id}', 'ArtController@getShow');
    Route::post('art/alldel', 'ArtController@postAlldel');
    Route::post('art/listorder', 'ArtController@postListorder');
    // database
    Route::get('database/export', 'DatabaseController@getExport');
    Route::post('database/export', 'DatabaseController@postExport');
    Route::get('database/import/{pre?}', 'DatabaseController@getImport');
    Route::post('database/delfile', 'DatabaseController@postDelfile');
    // type
    Route::get('type/index/{pid?}', 'Common\TypeController@getIndex');
    Route::get('type/add/{id?}', 'Common\TypeController@getAdd');
    Route::post('type/add/{id?}', 'Common\TypeController@postAdd');
    Route::get('type/edit/{id?}', 'Common\TypeController@getEdit');
    Route::post('type/edit/{id?}', 'Common\TypeController@postEdit');
    Route::get('type/del/{id?}', 'Common\TypeController@getDel');
    // 会员组
    Route::get('group/index', 'User\GroupController@getIndex');
    Route::get('group/add', 'User\GroupController@getAdd');
    Route::post('group/add', 'User\GroupController@postAdd');
    Route::get('group/edit/{id}', 'User\GroupController@getEdit');
    Route::post('group/edit/{id}', 'User\GroupController@postEdit');
    Route::get('group/del/{id}', 'User\GroupController@getDel');
    // 会员
    Route::get('user/index', 'User\UserController@getIndex');
    Route::get('user/edit/{id}', 'User\UserController@getEdit');
    Route::post('user/edit/{id}', 'User\UserController@postEdit');
    Route::get('user/status/{id}/{status}', 'User\UserController@getStatus');
    Route::get('user/chong/{id}', 'User\UserController@getChong');
    Route::post('user/chong/{id}', 'User\UserController@postChong');
    Route::get('user/consumed/{id}', 'User\UserController@getConsumed');
    Route::post('user/consumed/{id}', 'User\UserController@postConsumed');
    Route::get('user/consume/{id}', 'User\UserController@getConsume');
    Route::get('user/address/{id}', 'User\UserController@getAddress');
    Route::get('user/ranking', 'User\UserController@getConsumeRanking');
    Route::get('user/excel', 'User\UserController@getExcel');
});
