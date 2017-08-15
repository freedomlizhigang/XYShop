@extends('home.layout_simple')


@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection

<!-- 内容 -->
@section('content')

<section class="wrap_simple box clearfix overh">
	<h2 class="wrap_s_t2">收货人信息</h2>
	
	<h2 class="wrap_s_t2">送货清单</h2>
	<table class="table table_cart">
		<thead>
			<tr>
				<th>商品</th>
				<th width="80">单价</th>
				<th width="120">数量</th>
			</tr>
		</thead>
		@foreach($goodlists as $g)
		<tr class="g_tr_{{ $g->id }}">
			<td>
				<a href="{{ $g->url }}" class="pull-left wrap_s_thumb"><img src="{{ $g->good->thumb }}" alt="{{ $g->good_title }}" width="100"></a>
				<div class="cart_con">
					<h4 class="wrap_s_t4"><a href="{{ $g->url }}">{{ $g->good_title }}</a></h4
					>
					@if($g->good_spec_name != '')<p class="wrap_s_p">{{ $g->good_spec_name }}</p>@endif
				</div>
			</td>
			<td>
				<span class="good_prices">￥{{ $g->price }}</span>
			</td>
			<td>
				{{ $g->nums }}
			</td>
		</tr>
		@endforeach
	</table>
	<div class="cart_send clearfix">
		<div class="order_people_info">
			<p class="text-right">应付总额：<strong class="total_prices text-right color_2">￥{{ $total_prices }}</strong></p>
			<p class="order_address"></p>
		</div>
		<div class="btn_addorder pull-right">提交订单</div>
	</div>
</section>
<script src="{{ $sites['static']}}home/js/cookie.js"></script>
<script>
	$(function(){
		// 购物车数量
		var uid = "{{ session('member')->id }}";
		var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
		// 提交订单页面
		$('.sendtoconfirm').on('click',function(){
			if(before_request == 0)return false;
	    	var cid = '.';
	    	$('.selected_checkbox').each(function(){
	    		var that = $(this);
		    	var id = that.val();
				// 判断是选中还是没选中
				if (that.is(':checked')) {
					cid += id + '.';
				}
	    	});
	    	before_request = 0;
			$.post(host+'shop/orderinfo',{cid:cid},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
					$('.alert_msg').text('提交成功！').slideToggle().delay(1500).slideToggle();
					setTimeout(function(){
						window.location.href = "{{ url('shop/orderinfo') }}" + "?rid=" + ss.msg;
					},1500);
				}
				else
				{
					$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
				}
				before_request = 1;
				return;
			}).error(function() {
				before_request = 1;
				return;
			});
		});

	})
</script>
@endsection