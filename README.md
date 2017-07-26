<!--  -->

###待改bug


<!-- 完成的 -->
###完成的

系统基于Laravel 5.4，认证使用了RBAC，RBAC主要产生后台菜单

样式表，bootstrap

rbac 中间件控制打开页面是否有权限，同时判断是否登陆

添加调试工具Debugbar http://laravelacademy.org/post/2774.html，主页里关闭调试

提示信息，使用一次性session，在back()或者redirect()后->with('message','信息');

数据库备份功能（改造自V9）

附件删除

商城，分类下可筛选，库存及属性按sku来进行设计

下单及支付过程完整，支付使用包(omnipay-alipay/omnipay-wechatpay)来完成，目前只支持支付宝与微信，微信做了扫码支付功能

微信扫码登录功能完成，oauth的认证使用的是laravel-socialite包，PC与微信同步使用的是数据库存根auth_id的办法，pc端ajax轮询

后台订单管理

公用模块有：地区、社区、品牌、广告位、广告

促销活动包含：团购、活动、满赠、抢购、优惠券
