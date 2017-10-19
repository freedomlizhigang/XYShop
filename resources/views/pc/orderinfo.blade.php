@extends('pc.layout_simple')


@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection

<!-- 内容 -->
@section('content')

<div class="box">
	<p class="order_cart_t">填写并核对订单信息</p>
</div>
<section class="wrap_simple box clearfix overh">
	<h2 class="wrap_s_t2">收货人信息</h2>
	<ul class="list_address">
		@foreach($address as $a)
		<li data-aid="{{ $a->id }}" @if($a->default) class="active" @endif>
			<span class="l_a_left">
				{{ $a->people }}
			</span>
			<span class="l_a_right">
				{{ $a->people }} {{ $a->areaname }} {{ $a->address }} {{ $a->phone }}
			</span>
		</li>
		@endforeach
		<input type="hidden" name="aid" class="address_id" value="0">
		<input type="hidden" name="ziti" class="ziti" value="0">
	</ul>
	<div class="add_address">
		<span class="add_address_btn">新增收货地址</span>
	</div>
	<hr>

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
				<input type="hidden" class="cart_ids" value="{{ $g->id }}">
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
				{{ $g->nums }}
			</td>
		</tr>
		@endforeach
	</table>

	<hr>
	<h2 class="wrap_s_t2">发票信息</h2>
	<div class="orderinfo_fp">
		不开发票
	</div>
	<hr>
	<h2 class="wrap_s_t2">使用优惠</h2>
	<ul class="orderinfo_coupon clearfix">
		@foreach($coupon as $c)
		<li>
			<h4 class="coupon_t4"><span class="coupon_price">￥{{ $c->lessprice }}</span>满{{ $price }}</h4>
			<p class="coupon_time">有效期至{{ $c->endtime->format('Y-m-d') }}</p>
		</li>
		@endforeach
		<input type="hidden" name="yid" class="coupon" value="">
	</ul>
</section>
<div class="mt20 box clearfix">
	<textarea name="mark" class="mark form-control mt20" rows="4" placeholder="请填写备注信息.."></textarea>
	<div class="order_people_info">
		<p class="text-right">应付总额：<strong class="total_prices text-right color_2">￥{{ $total_prices }}</strong></p>
		<p class="order_address"></p>
	</div>
	<div class="btn_addorder btn_vice btn-lg mt20 pull-right">提交订单</div>
</div>
<div class="pop dn">
	<div class="pop_con pr">
		<span class="pop_close ps iconfont icon-close"></span>
		<h4 class="pop_t">新增收货人信息</h4>
		<div class="pd15">
			<form action="#" class="form-inline">
				<div class="form-group">
					<label for="#"><span class="color_main">*</span>所在地区</label>
					<select name="area1" id="area1" onchange="get_area(this.value,'area2',0)" class="form-control">
						<option value="">省份</option>
					</select>
					<select name="area2" id="area2" onchange="get_area(this.value,'area3',0)" class="form-control">
						<option value="">城市</option>
					</select>
					<select name="area3" id="area3" onchange="get_community(this.value,'area4',0)" class="form-control">
						<option value="">县区</option>
					</select>
					<select name="area4" id="area4" class="form-control">
						<option value="">乡镇</option>
					</select>
				</div>
				<div class="form-group">
					<label for="#"><span class="color_main">*</span>收货人</label>
					<input type="text" name="people" class="form-control input-md address_p">
				</div>
				<div class="form-group">
					<label for="#"><span class="color_main">*</span>详细地址</label>
					<input type="text" name="address" class="form-control input-md address_a">
				</div>
				<div class="form-group">
					<label for="#"><span class="color_main">*</span>手机号码</label>
					<input type="text" name="phone" class="form-control input-md address_tel">
				</div>
				<div class="form-group">
					<span class="pop_btn btn_addaddress">保存收货人信息</span>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$(function(){
		// 购物车数量
		var uid = "{{ session('member')->id }}";
		var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
		// 加载省份信息
		get_area(0,'area1',0);
		// 选择收货人
		$(document).on('click','.l_a_left',function(){
			var that = $(this).parent('li');
			$('.list_address li').removeClass('active');
			that.addClass('active');
			var aid = that.attr('data-aid');
			$('.address_id').val(aid);
		});
		// 添加收货人信息
		$('.add_address_btn').click(function(){
			$('.pop').show();
		});
		$('.btn_addaddress').click(function(){
			var area1 = $('#area1').val();
			var area2 = $('#area2').val();
			var area3 = $('#area3').val();
			var area4 = $('#area4').val();
			var people = $('.address_p').val();
			var address = $('.address_a').val();
			var phone = $('.address_tel').val();
			before_request = 0;
			$.post(host+'api/user/ajax_address',{uid:uid,area1:area1,area2:area2,area3:area3,area4:area4,people:people,address:address,phone:phone,},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
					$('.alert_msg').text('提交成功！').slideToggle().delay(1500).slideToggle();
					// 插入到列表里
					$('.pop').hide();
					$('.list_address li').removeClass('active');
					$('.list_address').prepend(ss.msg);
					$('.address_id').val($('.list_address li.active').attr('data-aid'));
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
		// 提交订单功能
		$('.btn_addorder').on('click',function() {
			if(before_request == 0)return false;
			var that = $(this);
	    	var aid = $('.address_id').val();
	    	var ziti = $('.ziti').val();
	    	var cid = '"';
	    	$('.cart_ids').each(function(index, el) {
                cid += ',' + $(this).val();
	    	});
	    	cid += '"';
	    	var points = "{{ session('member')->points }}";
	    	var yid = $('.yid').val();
	    	var mark = $('.mark').val();
	    	before_request = 0;
			$.post( host +'api/good/addorder',{cid:cid,uid:uid,aid:aid,ziti:ziti,yid:yid,mark:mark,points:points},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
					$('.alert_msg').text('创建订单成功，请及时支付~').slideToggle().delay(1500).slideToggle();
					setTimeout(function(){
		    			// 跳转到订单页面
		    			window.location.href = "{{ url('shop/pay') }}" + '/' + ss.msg;
					},1500);
				}
				else
				{
					// alert(ss.msg);
					$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
				}
				ss = null;
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