@extends('home.layout')


@section('title')
    <title>购物车-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
	<div class="container-fluid pb50 mt10">
		<!-- 选择送货方式 -->
		<div class="bgf ship">
			<div class="ship_con">
	  			@foreach($address as $y)
	  			@if($y->default)
				<h3 class="h3_cate"><span class="h3_cate_span">配送至</span></h3>
	  			  <label>
	  			    <input type="radio" name="addid" value="{{ $y->id }}" checked="checked" class="addressid">
	  			    <h4>{{ $y->people }}：{{ $y->phone }}</h4>
	  			    <p class="mt5">{{ $y->address }}</p>
	  			  </label>
	  			@endif
	  			@endforeach
	  		</div>
			<p class="text-center btn_ship"><span class="glyphicon glyphicon-plus"></span> 选择送货方式</p>
		</div>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">选择送货方式</h4>
			      </div>
		      	<div class="modal-body">
		      		<!-- 送货地址 -->
		      		<h3 class="h3_cate"><span class="h3_cate_span">配送至</span></h3>
		      		<ul class="mt10">
		      			@foreach($address as $y)
		      			<li class="radio ship_li">
		      			  <label>
		      			    <input type="radio" name="addid" value="{{ $y->id }}" class="addressid">
		      			    <h4>{{ $y->people }}：{{ $y->phone }}</h4>
		      			    <p class="mt5">{{ $y->address }}</p>
		      			  </label>
		      			</li>
		      			@endforeach
		      			<a href="{{ url('/user/address/add') }}" class="btn btn-sm btn-success">添加配送地址</a>
		      		</ul>
		      		
		      		<!-- 自提点 -->
		      		<h3 class="h3_cate"><span class="h3_cate_span">自提</span></h3>
		      		<ul class="mt10">
		      			@foreach($ziti as $y)
		      			<li class="radio ship_li">
		      			  <label>
		      			    <input type="radio" name="ziti" value="{{ $y->id }}" class="zitiid">
		      			    <h4>{{ $y->address }}</h4>
		      			    <p class="mt5">{{ $y->phone }}</p>
		      			  </label>
		      			</li>
		      			@endforeach
		      		</ul>
		      	</div>
		    </div>
		  </div>
		</div>

<form action="{{ url('shop/addorder') }}">
	{{ csrf_field() }}

	
			<h3 class="h3_cate mt10"><span class="h3_cate_span">购物车</span></h3>
			<div class="good_cart_list overh">
				@foreach($goodlists as $l)
				<div class="mt5 good_cart_list_div">
					<div class="checkbox pull-left mr10" style="padding-top: 30px;">
					<label>
					    <input type="checkbox" class="selected_checkbox cart_ids" data-gid="{{ $l->id }}" name="cid[]" @if($l->selected) checked="checked"@endif value="{{ $l->id }}">
				  	</label>
					</div>
					<div class="media">
						<a href="{{ url('/shop/good',['id'=>$l->good_id,'format'=>$l->format['format']]) }}" class="pull-left"><img src="{{ $l->good->thumb }}" width="100" class="media-object img-thumbnail" alt=""></a>
						<div class="media-body">
							<h4 class="mt5 cart_h4"><a href="{{ url('/shop/good',['id'=>$l->good_id]) }}">{{ $l->good_title }}</a><span class="remove_cart glyphicon glyphicon-trash ml10" data-cid="{{ $l->id }}"></span></h4>
							<!-- 删除功能 -->
							<!-- <span class="remove_cart glyphicon glyphicon-trash ml10" data-gid="{{ $l->id }}"></span> -->
							<!-- end 删除功能 -->
							@if($l->good_spec_name != '')<span class="btn btn-sm btn-info mt10">{{ $l->good_spec_name }}</span>@endif
							<div class="row mt5">
								
								<div class="col-xs-6">
									<!-- 价格 -->
									<p class="fs12">价格：<span class="good_prices color_l">￥{{ $l->price }}</span></p>

									<span class="one_total_price hidden total_price_{{ $l->id }}" data-price="{{ $l->total_prices }}">{{ $l->total_prices }}</span>
								</div>

								<div class="col-xs-6">
									<!-- 数量 -->
									<input type="hidden" min="1" name="num[]" value="{{ $l->num }}" data-cid="{{ $l->id }}" data-price="{{ $l->price }}" class="form-control input-nums change_cart cart_num_{{ $l->id }}">
									
									<div class="cart_nums clearfix pull-left">
										<div class="cart_dec_cart" data-gid="{{ $l->id }}">-</div>
										<div class="cart_num_cart cart_num_cart_{{ $l->id }}">{{ $l->nums }}</div>
										<div class="cart_inc_cart" data-gid="{{ $l->id }}">+</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				@endforeach
			</div>
			

			<!-- 满赠 -->
			@if($mz->count() > 0)
			<div class="bgf mt10">
				<h3 class="h3_cate"><span class="h3_cate_span">赠品</span></h3>
				<ul class="mt10 cart_mz">
					@foreach($mz as $y)
					<li>
						<h5>{{ $y->title }}</h5>
					    <p class="small">{{ $y->good->title }}</p>
					</li>
					@endforeach
				</ul>
			</div>
			@endif


			<!-- 优惠券 -->
			@if($yhq->count() > 0)
			<div class="bgf mt10">
				<h3 class="h3_cate"><span class="h3_cate_span">使用优惠券</span></h3>
				<ul class="mt10 cart_yhq row">
					@foreach($yhq as $y)
					<li class="col-xs-6 yhqid" data-yid="{{ $y->id }}">
						<div class="radio cart_yhq_list">
						  <label>
						    {{ $y->yhq->title }}
						  </label>
					  	</div>
					</li>
					@endforeach
				</ul>
			</div>
			@endif

			
	<div class="bgf mt10">
		<textarea name="mark" class="form-control mark" rows="4" placeholder="备注"></textarea>
	</div>
</div>
	

	<!-- 添加购物车 -->
	<div class="good_alert clearfix navbar navbar-fixed-bottom">
		<div class="cart_total pr">
			总计：<strong class="total_prices text-right color_2">￥{{ $total_prices }}</strong>
		</div>

			<input type="hidden" name="yid" class="yid" value="0">
			<input type="hidden" name="aid" class="aid" value="0">
			<input type="hidden" name="ziti" class="ziti" value="0">
			<input type="hidden" name="tt" value="{{ microtime(true) }}">
			<div class="alert_addorder">提交订单</div> 
	</div>
</form>

<!-- 加载中 -->
<div class="pos_bg">
	<div class="pos_text">提交中，请稍候...</div>
</div>

<script>
	$(function(){
		// 购物车数量
		var uid = "{{ session('member')->id }}";
		var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次

		$('.aid').val($('.addressid:checked').val());

		$('.addressid').change(function() {
			var aid = $(this).val();
			$('.aid').val(aid);
			var html = '<h3 class="h3_cate"><span class="h3_cate_span">送货至</span></h3>' + $(this).parent('label').parent('.ship_li').html();
			$('.ship_con').html(html);
			$('#myModal').modal('hide');
		});

		$('.zitiid').change(function() {
			var aid = $(this).val();
			$('.ziti').val(aid);
			$('.aid').val(0);
			var html = '<h3 class="h3_cate"><span class="h3_cate_span">自提点</span></h3>' + $(this).parent('label').parent('.ship_li').html();
			$('.ship_con').html(html);
			$('#myModal').modal('hide');
		});
		$('.btn_ship').click(function(){
			$('#myModal').modal('show');
		});

		// 优惠券比价
		$('.yhqid').on('click',function() {
			if(before_request == 0)return false;
			var that = $(this);
			var yid = that.attr('data-yid');
			var total_prices = $('.total_prices').text();
			// 查一下是否比总价多，不多，不可用
			$.post( host +'api/common/yhq/price',{yid:yid,total_prices:total_prices},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
					$('.yid').val(yid);
					// 选择优惠券  
					$('.cart_yhq_list').removeClass('active');
					that.children('.cart_yhq_list').addClass('active');
				}
				else
				{
					alert('总价格低于优惠券需要！');
					$('.cart_yhq_list').removeClass('active');
				}
				before_request = 1;
				return;
			}).error(function() {
				before_request = 1;
				return;
			});
		});

		// 提交订单功能
		$('.alert_addorder').on('click',function() {
			if(before_request == 0)return false;
			$('.pos_bg').show();
			var that = $(this);
	    	var aid = $('.aid').val();
	    	var ziti = $('.ziti').val();
	    	var cid = '"';
	    	$('.cart_ids').each(function(index, el) {
			    if ($(this).prop('checked')) { 
	                cid += ',' + $(this).val();
	            }
	    	});
	    	cid += '"';
	    	var points = "{{ session('member')->points }}";
	    	var yid = $('.yid').val();
	    	var mark = $('.mark').val();
	    	before_request = 0;
			$.post( host +'api/common/good/addorder',{cid:cid,uid:uid,aid:aid,ziti:ziti,yid:yid,mark:mark,points:points},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
	    			// 跳转到订单页面
	    			window.location.href = "{{ url('shop/addorder') }}" + '/' + ss.msg;
				}
				else
				{
	    			$('.pos_bg').hide();
					// console.log(d);
					alert(ss.msg);
				}
				ss = null;
				before_request = 1;
				return;
			}).error(function() {
				before_request = 1;
				return;
			});
		});
		

		// 移除购物车
		$(".remove_cart").on('click',function(){
			if(before_request == 0)return false;
			var that = $(this);
	    	var cid = that.attr('data-cid');
	    	before_request = 0;
			$.post(host+'api/common/good/removecart',{cid:cid},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
	    			that.parents('.good_cart_list_div').remove();
	    			// 重新取购物车数量，计算总价
	    			total_prices();
				}
				else
				{
					alert(ss.msg);
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