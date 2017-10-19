@extends('pc.layout_simple')


@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection

<!-- 内容 -->
@section('content')
<div class="box">
	<p class="order_cart_t">订单结算页面</p>
</div>
<form action="{{ url('shop/order/pay',['oid'=>$order->id]) }}" method="get">
<section class="wrap_simple box clearfix overh">
	<div class="text-center mt20">
	   <h4>订单已提交!</h4>
	    <p class="mt10">单号：<span class="text-info">{{ $order->order_id }}</span></p>
	</div>
	<hr>
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
</section>
<div class="box mt20 clearfix">
	<p class="text-right cart_send">总计：<strong class="total_prices text-right color_2">￥{{ $order->total_prices }}</strong></p>
	<input type="submit" class="sendtoconfirm mt10 btn_vice btn-lg pull-right" value="去支付">
</div>
</form>
<script>
	$(function(){

	})
</script>
@endsection