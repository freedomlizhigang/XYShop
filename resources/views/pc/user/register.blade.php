@extends('pc.user.layout')

@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection


<!-- 内容 -->
@section('content')
<!-- form_box -->
<div class="form_login_bg">
	<section class="box clearfix">
		<div class="login_box_right">
			<h3><span class="tab_t active">用户注册</span><span class="tab_t getcode">微信直接登录</span></h3>
			<div class="tab_login_div">
				<form action="{{ url('/user/register') }}" method="post" class="login_form form-inline">
					{{ csrf_field() }}
					<div class="form-group clearfix">
						<div class="input-left iconfont icon-people"></div>
						<input type="text" class="form-control" name="username" placeholder="请输入注册的用户名...">
					</div>
					<div class="form-group clearfix">
						<div class="input-left iconfont icon-cascades"></div>
						<input type="text" class="form-control" name="email" placeholder="请输入常用邮箱...">
					</div>
					<div class="form-group clearfix">
						<div class="input-left iconfont icon-attention_light"></div>
						<input type="password" class="form-control" name="password" placeholder="请输入密码...">
					</div>
					<div class="form-group clearfix">
						<input type="submit" class="login_btn" value="立即注册">
					</div>
				</form>
			</div>
			<div class="tab_login_div dn">
				<img src="" class="img-responsive wxcode center-block" width="200" height="200" alt="扫码">
				<p class="text-center">二维码有效期 5 分钟</p>
				<p class="text-center">打开手机微信扫码注册</p>
				<input type="hidden" value="xyshop" class="sid">
			</div>
			<div class="mt15 text-right">
				<a href="{{ url('/user/login') }}" class="login_a_h">已有用户，直接登录</a>
			</div>

		</div>
	</section>
	<script>
		$(function(){
			$('.tab_t').click(function(){
				var thisIndex = $(this).index();
				$('.tab_t').removeClass('active').eq(thisIndex).addClass('active');
				$('.tab_login_div').hide().eq(thisIndex).show();
			});
			// 点扫码时加载二维码
			$('.getcode').click(function() {
				$.get(host + 'api/auth/wxregcode',function(d){
					var ss = jQuery.parseJSON(d);
					if (ss.src != 'undefind') {
						$('.wxcode').attr('src',ss.src);
						$('.sid').val(ss.sid);
					}
					else{
						console.log(d);
					}
				});
				// 轮询是关键~~
				var ref = "{!! $ref !!}";
				var isLogin = setInterval(function(){
					var thisSid = $('.sid').val();
					$.get(host + 'oauth/wxscancode',{sid:thisSid},function(d){
						if (d != '0') {
							clearInterval(isLogin);
							// 跳转
							window.location.href = ref;  
						}
						else{
							console.log(d);
						}
					});
				},2000);
			});
		});
	</script>
</div>
@endsection