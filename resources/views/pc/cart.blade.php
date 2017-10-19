@extends('pc.layout_simple')


@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection

<!-- 内容 -->
@section('content')

<div class="box">
	<p class="order_cart_t">购物车页面</p>
</div>
<section class="wrap_simple box clearfix overh">
	<h2 class="wrap_s_t2">全部商品 {{ count($goodlists) }}</h2>
	<table class="table table_cart">
		<thead>
			<tr>
				<th width="50"><input type="checkbox" checked="checked" class="checkall"></th>
				<th>商品</th>
				<th width="80">单价</th>
				<th width="120">数量</th>
				<th width="100">小计</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		@foreach($goodlists as $g)
		<tr class="g_tr_{{ $g->id }}">
			<td><input type="checkbox" class="selected_checkbox check_s cart_ids" data-gid="{{ $g->id }}" name="cid[]" @if($g->selected) checked="checked"@endif value="{{ $g->id }}"></td>
			<td>
				<a href="{{ $g->good->url }}" class="pull-left wrap_s_thumb"><img src="{{ $g->good->thumb }}" alt="{{ $g->good_title }}" width="100"></a>
				<div class="cart_con">
					<h4 class="wrap_s_t4"><a href="{{ $g->good->url }}">{{ $g->good_title }}</a></h4
					>
					@if($g->good_spec_name != '')<p class="wrap_s_p">{{ $g->good_spec_name }}</p>@endif
				</div>
			</td>
			<td>
				<span class="good_prices">￥{{ $g->price }}</span>
			</td>
			<td>
				<!-- 数量 -->
				<input type="hidden" min="1" value="{{ $g->num }}" data-cid="{{ $g->id }}" data-price="{{ $g->price }}" class="form-control input-nums change_cart cart_num_{{ $g->id }}">
				<div class="cart_nums clearfix">
					<div class="cart_dec_cart" data-gid="{{ $g->id }}">-</div>
					<div class="cart_num_cart cart_num_cart_{{ $g->id }}">{{ $g->nums }}</div>
					<div class="cart_inc_cart" data-gid="{{ $g->id }}">+</div>
				</div>
			</td>
			<td>
				<strong class="one_total_price total_price_{{ $g->id }}" data-price="{{ $g->total_prices }}">{{ $g->total_prices }}</strong>
			</td>
			<td>
				<span class="">移入收藏</span>
				<span class="remove_cart confirm" data-cid="{{ $g->id }}">删除</span>
			</td>
		</tr>
		@endforeach
	</table>
</section>
<div class="box mt20 clearfix">
	<p class="text-right cart_send">总计：<strong class="total_prices text-right color_2">￥{{ $total_prices }}</strong></p>
	<div class="sendtoconfirm mt10 btn_vice btn-lg pull-right">去结算</div>
</div>
<script>
	$(function(){
		$(".checkall").bind('change',function(){
			if($(this).is(":checked"))
			{
				$(".check_s").each(function(s){
					$(".check_s").eq(s).prop("checked",true);
				});
			}
			else
			{
				$(".check_s").each(function(s){
					$(".check_s").eq(s).prop("checked",false);
				});
			}
		});
		// 购物车数量
		var uid = "{{ session('member')->id }}";
		var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次

		// 移除购物车
		$(".remove_cart").on('click',function(){
			if(before_request == 0)return false;
			var that = $(this);
	    	var cid = that.attr('data-cid');
	    	before_request = 0;
			$.post(host+'api/good/removecart',{cid:cid},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
	    			// 重新取购物车数量，计算总价
	    			total_prices();
					$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
					// 删除对应的结构
					$('.g_tr_' + cid).remove();
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

		// 提交到结算页面
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