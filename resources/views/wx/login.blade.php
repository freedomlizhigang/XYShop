@extends('default.layout')

@section('title')
    <title>微信登录-{{ cache('config.sitename') }}</title>
@endsection

@section('banner')
    @include('default.banner')
@endsection

@section('content')
	<div class="container mt20">
		<h3 class="h3_cate"><span class="h3_cate_span">扫码登录</span></h3>
		<img src="{{ $src }}" alt="">
	</div>
	<script>
		$(function(){
			// 轮询是关键~~
			var isLogin = setInterval(function(){
				$.get(host + 'oauth/wxislogin',{sid:"{{ $sid }}"},function(d){
					if (d != 0) {
						console.log(d);
						clearInterval(isLogin);
						// 跳转
						window.location.href= host;  
					}
					else{
						console.log('dd');
					}
				});
			},3000);
		});
	</script>
@endsection