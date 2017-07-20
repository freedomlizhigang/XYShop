@extends('home.layout')


@section('title')
    <title>提交订单成功-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
<section class="container-fluid">
	<div class="text-center mt20">
	   <h4><span class="glyphicon glyphicon-ok-sign text-success"></span>订单已提交!</h4>
	   <p class="mt5">（请尽快完成支付）</p>
	    <p>单号：<span class="text-info">{{ $order->order_id }}</span></p>
	    <p>支付金额：<span class="text-danger fz14">￥{{ $order->total_prices }}</span></p>
	</div>
	<hr>
	<form action="{{ url('shop/order/pay',['oid'=>$order->id]) }}" method="post">
		{{ csrf_field() }}
		<div class="row">
			@foreach($paylist as $l)
			<div class="col-xs-12 col-sm-6 col-md-2 mt10">
				<label class="paylist_label">
					<input type="radio" name="pay" value="{{ $l->id }}">
					<img src="{{ $l->thumb }}" width="24" height="auto" alt="">
					{{ $l->content }}
	  			</label>
			</div>
			@endforeach
		</div>
		<div class="mt20 clearfix">
			<a href="{{ url('/') }}" class="btn btn-default">继续购物</a>
			<button type="submit" class="btn btn-success">立即支付</button> 
		</div>
	</form>
	<!-- 送货提示 -->
	<div class="shop_tip mt10">
		<table class="table text-center table-bordered">
			<tr>
				<td>下单时间</td>
				<td>配送时间</td>
			</tr>
			<tr>
				<td>10:00-16:00下单</td>
				<td>18:00前送达</td>
			</tr>
			<tr>
				<td>16:00-22:00下单</td>
				<td>次日10:00前送达</td>
			</tr>
			<tr>
				<td>22:00-10:00(次日)下单</td>
				<td>次日12:00前送达</td>
			</tr>
			<tr>
				<td colspan="2">配送范围：外环内(包含太阳城、橡胶城)</td>
			</tr>
		</table>
	</div>
</section>

@include('home.foot')
@endsection