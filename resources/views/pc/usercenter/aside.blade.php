<aside class="pull-left user_left">
	<h3 class="u_c_l_t3">订单中心</h3>
	<ul class="list_u_c_l">
		<li><a href="{{ url('/order/list') }}">我的订单</a></li>
		<li><a href="{{ url('/order/tuan') }}">我的团购</a></li>
		<li><a href="{{ url('/user/coupon') }}">优惠券</a></li>
		<li><a href="{{ url('/user/returngood') }}">退货管理</a></li>
		<li><a href="{{ url('/user/returngood') }}">评价晒单</a></li>
		<li><a href="{{ url('/user/collect') }}">关注的商品</a></li>
	</ul>
	<h3 class="u_c_l_t3">客户服务</h3>
	<ul class="list_u_c_l">
		<li><a href="{{ url('/user/card') }}">充值卡激活</a></li>
		<li><a href="{{ url('/user/consume') }}">消费记录</a></li>
	</ul>
	<h3 class="u_c_l_t3">设置</h3>
	<ul class="list_u_c_l">
		<li><a href="{{ url('/user/info') }}">我的信息</a></li>
		<li><a href="{{ url('/user/address') }}">地址管理</a></li>
		<li><a href="{{ url('/user/pwd') }}">修改密码</a></li>
		<li><a href="{{ url('/user/logout') }}">退出登录</a></li>
	</ul>
</aside>