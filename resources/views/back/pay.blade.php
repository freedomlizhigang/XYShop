@extends('home.layout')

@section('title')
    <title>微信支付-{{ cache('config')['sitename'] }}</title>
@endsection

@section('banner')
    @include('default.banner')
@endsection

@section('content')
	<div class="container-fluid mt20">
		<h3 class="h3_cate"><span class="h3_cate_span">扫码支付</span></h3>
		<img src="{{ $src }}" alt="">
	</div>
	<script>
		$(function(){
			var isLogin = setInterval(function(){
				$.get(host + 'shop/ispay',{orderid:"{{ $oid }}"},function(d){
					if (d != 0) {
						console.log(d);
						clearInterval(isLogin);
						// 跳转
						window.location.href= host + '/shop/order';  
					}
					else{
						console.log('dd');
					}
				});
			},3000);
		});
	</script>
@include('home.foot')
@endsection