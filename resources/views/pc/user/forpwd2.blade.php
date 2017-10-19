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
			<h3><span class="tab_t active">修改密码</span></h3>
			<div class="tab_login_div">
				<form action="{{ url('/user/forpwd2') }}" method="post" class="login_form form-inline">
					{{ csrf_field() }}
					<div class="form-group clearfix">
						<div class="input-left iconfont icon-attention_light"></div>
						<input type="password" class="form-control" name="password" placeholder="请输入新密码...">
					</div>
					<div class="form-group clearfix">
						<div class="input-left iconfont icon-cascades"></div>
						<input type="text" class="form-control" name="code" placeholder="请输入验证码...">
					</div>
					<div class="form-group clearfix">
						<input type="submit" class="login_btn" value="确认修改">
					</div>
				</form>
			</div>
			<div class="mt15 text-right">
				<a href="{{ url('/user/register') }}" class="login_a_h">立即注册</a>
			</div>
		</div>
	</section>
</div>
@endsection