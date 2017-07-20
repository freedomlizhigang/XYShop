@extends('home.layout')

@section('title')
    <title>订单支付成功-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
<div class="bgf">

	<div class="container-fluid mt10 pb50">
		<div class="text-center mt20">
		   <h4><span class="glyphicon glyphicon-ok-sign text-success"></span>订单支付成功!</h4>
		   <p class="mt5">（请尽快完成支付）</p>
		    <p>单号：<span class="text-info">{{ $info->order_id }}</span></p>
		    <p>支付金额：<span class="text-danger fz14">￥{{ $info->total_prices }}</span></p>
		</div>
		<a href="{{ url('/') }}" class="btn btn-success center-block mt20">继续购物</a>
	</div>

</div>
@include('home.foot')
@endsection