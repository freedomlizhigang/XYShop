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
			<h3><span class="tab_t active">用户登录</span><span class="tab_t getcode">扫码登录</span></h3>
			<div class="tab_login_div">
				<form action="{{ url('/user/login') }}" method="post" class="login_form form-inline">
					{{ csrf_field() }}
					<div class="form-group clearfix">
						<div class="input-left iconfont icon-people"></div>
						<input type="text" class="form-control" name="username" placeholder="请输入注册的用户名...">
					</div>
					<div class="form-group clearfix">
						<div class="input-left iconfont icon-attention_light"></div>
						<input type="password" class="form-control" name="password" placeholder="请输入密码...">
					</div>
					<div class="mt15 text-right">
						<a href="{{ url('/user/forpwd') }}" class="login_a">忘记密码</a>
					</div>
					<div class="form-group clearfix">
						<input type="submit" class="login_btn" value="确认登录">
					</div>
				</form>
			</div>
			<div class="tab_login_div dn">
				<img src="" class="img-responsive wxcode center-block" width="200" height="200" alt="扫码">
				<p class="text-center">二维码有效期 5 分钟</p>
				<p class="text-center">打开手机微信扫码登陆</p>
				<input type="hidden" value="xyshop" class="sid">
			</div>
			<div class="mt15 text-right">
				<a href="{{ url('/user/register') }}" class="login_a_h">立即注册</a>
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
				$.get(host + 'api/auth/wxlogincode',function(d){
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