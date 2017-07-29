@extends('home.layout')


@section('title')

    <title>{{ $info->title }}</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection



<!-- 内容 -->
@section('content')
<link href="{{ $sites['static']}}home/css/star-rating.min.css" rel="stylesheet">
<script src="{{ $sites['static']}}home/js/star-rating.min.js"></script>

<section class="container-fluid good_content">
    <div class="good_top">
		<div class="good_thumb"><img src="{{ $info->thumb }}" class="img-responsive" alt="{{ $info->title }}"></div>
		<div class="good_bgf">
			<div class="good_show">
				@if($info->isxs)
				<span class="tuan_times">限时购： {{ $info->endtime }} 结束</span>
				@endif
				@if($info->isxl)
				<span class="tuan_times">限每人 {{ $info->xlnums }} 件</span>
				@endif
				<h1 class="good_show_title mt10">
					@if($info->isxs)
					<span class="tags">限时</span>
					@endif
					@if($info->isxl)
					<span class="tags">限量</span>
					@endif
					<a href="{{ url('shop/good',['id'=>$info->id]) }}">{{ $info->title }}</a></h1>
					<!-- <h4>{{ $info->pronums }}</h4> -->
					<form action="{{ url('shop/addcart') }}" data-firstorder="{{ url('shop/firstorder') }}" class="form_addcart">
					<!-- 规格开始 -->
					@if(count($filter_spec) > 0)
					<table class="table mt5">
					@foreach($filter_spec as $ks => $gs)
						<tr>
							<td width="80" class="text-right">{{ $ks }}：</td>
							<td>
								@foreach($gs as $kks => $ggs)
								<span onclick="select_filter(this);" class="label label-default good_label @if($kks == 0) label-success @endif spec_item" data-item_id="{{ $ggs['item_id'] }}"><input type="radio" name="goods_spec[{{$ks}}]" class="hidden"@if($kks == 0) checked="checked"@endif value="{{ $ggs['item_id'] }}">{{ $ggs['item'] }}</span>
								@endforeach
								<input type="hidden" name="spec_key" class="spec_key" value="">
							</td>
						</tr>
					@endforeach
					</table>
					<script>
						$(function(){
							get_goods_price();
						})
	                    /**
	                     * 切换规格
	                     */
	                    function select_filter(obj)
	                    {
	                        $(obj).addClass('label-success').siblings('span').removeClass('label-success');
	                        $(obj).children('input').prop('checked','checked');
	                        $(obj).siblings('span').children('input').attr('checked',false);// 让隐藏的 单选按钮选中
	                        // 更新商品价格
	                        get_goods_price();
	                    }
	                    function get_goods_price()
				        {
				            var price = "{{$info->price}}"; // 商品起始价
				            var store = "{{$info->store}}"; // 商品起始库存
				            var spec_goods_price = {!! $good_spec_price !!};  // 规格 对应 价格 库存表 //alert(spec_goods_price['28_100']['price']);
				            // 如果有属性选择项
				            if(spec_goods_price != null && spec_goods_price !='')
				            {
				                goods_spec_arr = new Array();
				                $("input[name^='goods_spec']:checked").each(function(){
				                    goods_spec_arr.push($(this).val());
				                });
				                var spec_key = '_' + goods_spec_arr.sort(sortNumber).join('_') + '_';  //排序后组合成 key
				                // console.log(spec_key);
				                $(".spec_key").val(spec_key);
				                price = spec_goods_price[spec_key]['price']; // 找到对应规格的价格
				                store = spec_goods_price[spec_key]['store']; // 找到对应规格的库存
				            }
				            $('#store').html(store);    //对应规格库存显示出来
				            $(".price").html(price); // 变动价格显示
				            $("input[name='gp']").val(price);
				        }
				        /***用作 sort 排序用*/
				        function sortNumber(a,b)
				        {
				            return a - b;
				        }
	                </script>
					@endif
					<!-- 规格结束 -->

					<!-- 价格、库存，购物车 -->
					<input type="hidden" value="{{ $info->price }}" name="gp">
					<div class="row price_store mt10">
						<div class="col-xs-6">价格：￥<span class="price color_l">{{ $info->price }}</span></div>
						<div class="col-xs-6 text-right mt store">库存：<span id="store">{{ $info->store }}</span></div>
					</div>

					<div class="row ship">
						<div class="col-xs-4">
							<span class="glyphicon glyphicon-home"></span>送至
						</div>
						<div class="col-xs-4 text-center">
							衡水市桃城区
						</div>
						<div class="col-xs-4 text-right">
							免运费
						</div>
					</div>

					
					<!-- 加购物车的信息字段 -->
					<input type="hidden" value="{{ $info->id }}" name="gid">
					<input type="hidden" name="aid" class="aid" value="0">
					<input type="hidden" name="ziti" class="ziti" value="0">
					<input type="hidden" min="0" value="1" class="form-control cartnum" name="num">
				</form>
				<!-- 优惠券 -->
				@if($havyhq->count() > 0)
				<div class="yhq_lq clearfix mt10">
					<a href="{{ url('shop/yhq/index') }}">
						<img src="{{ $sites['static']}}home/images/yhq.png" class="img-responsive pull-left" width="74" alt="">
						<div class="yhq_font">
							<div class="pull-right yhq_r">
								>
							</div>
							@foreach($havyhq as $y)
							<p class="text-nowarp">{{ str_limit($y->title,20,'...') }}</p>
							@endforeach
						</div>
					</a>
				</div>
				@endif
			</div>
		</div>
	</div>

	
	<!-- 店铺 -->
	<div class="shop_info">
		<div class="row">
			<div class="col-xs-3">
				<img src="{{ $sites['static']}}home/images/jxf_logo.png" class="img-responsive" alt="">
			</div>
			<div class="col-xs-9">
				<h5>{{ cache('config')['sitename'] }}</h5>
				<p class="color_l"><span class="glyphicon glyphicon-ok-sign"></span>微信认证</p>
			</div>
		</div>
		<div class="row mt10 text-center">
			<div class="col-xs-6">
				<a href="{{ url('/shop/goodcate') }}" class="shop_info_a"><span class="glyphicon glyphicon-fullscreen"></span>全部分类</a>
			</div>
			<div class="col-xs-6">
				<a href="{{ url('/') }}" class="shop_info_a"><span class="glyphicon glyphicon-user"></span>商家首页</a>
			</div>
		</div>
	</div>
	<!-- 商品详情 -->
	<div class="good_show_con mt10">
		{!! $info->content !!}
	</div>
	@if($info->goodattr->count() > 0)
	<div class="good_show_con mt10">
		<h3 class="h3_cate"><span class="h3_cate_span">商品属性</span></h3>
		<table class="table table-bordered table-striped">
		@foreach($info->goodattr as $ga)
			<tr>
			<td width="100" class="text-right">{{ $ga->goodattr->name }}：</td>
			<td>
				@if(!is_array($ga->good_attr_value))
				{{ $ga->good_attr_value }}
				@else
				{{ implode(',',$ga->good_attr_value) }}
				@endif
			</td>
			</tr>
		@endforeach
		</table>
	</div>
	@endif
	@if($goodcomment->count() > 0)
	<div class="good_show_con mt10">
		<h3 class="h3_cate"><span class="h3_cate_span">商品评价</span></h3>
		<ul class="list_goodcomment">
			@foreach($goodcomment as $g)
			<li class="clearfix">
				<div class="row">
					<div class="col-xs-7">
						@if($g->user->thumb)
						<img src="{{ $g->user->thumb }}" class="img-circle good_comment_img" alt="{{ $g->user->nickname }}">
						@else
						<img src="{{ $sites['static']}}home/images/jxf_logo.png" class="img-circle good_comment_img" alt="{{ $g->user->nickname }}">
						@endif
						{{ $g->user->nickname }}
					</div>
					<div class="col-xs-5 text-right">
						<input class="score_name" value="{{ $g->score }}" type="number" name="data[score]" readonly="readonly" data-size="xs">
					</div>
				</div>
				<h5 class="text-success text-nowarp">{{ $g->title }}</h5>
				<p>{{$g->content}}</p>
			</li>
			@endforeach
			<script>
            	$(function(){
            		$(".score_name").rating({displayOnly:true});
            	});
            </script>
		</ul>
	</div>
	@endif
</section>

<!-- 添加购物车 -->
<div class="good_alert clearfix navbar navbar-fixed-bottom">
	<a class="good_cart_nums pr" href="{{ url('shop/cart') }}">
		<span class="glyphicon glyphicon-shopping-cart"></span>
		<div class="good_alert_num ps"></div>
	</a>
	<botton class="alert_addcart good_addcart">加入购物车</botton>
	<botton class="alert_addcart good_firstorder">直接购买</botton>
</div>


<div class="modal fade" id="myModal_order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      	<h4>{{ $info->title }}</h4>
      	<div class="clearfix mt10">
	      	<!-- 数量 -->
	        <div class="cart_nums clearfix pull-left">
	        	<div class="pull-left">数量：</div>
				<div class="first_cart_dec">-</div>
				<div class="first_cart_num">1</div>
				<div class="first_cart_inc">+</div>
			</div>
			<div class="firstorder btn btn-sm btn-success pull-right ml10">购买</div>
		</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      	<h4>{{ $info->title }}</h4>
      	<div class=" clearfix mt10">
	      	<!-- 数量 -->
	        <div class="cart_nums clearfix pull-left">
				<div class="cart_dec">-</div>
				<div class="cart_num">1</div>
				<div class="cart_inc">+</div>
			</div>
			<div class="addcart btn btn-sm btn-success pull-right ml10">添加</div>
		</div>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-success alert_good" style="display: none;" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <p>添加成功</p>
</div>

<script>
	$(function(){
		// 购物车数量
		var uid = "{{ !is_null(session('member')) ? session('member')->id : 0 }}";
		var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
		cartnum(uid);
		// 添加到购物车
		$('.addcart').on('click',function(event) {
			if(before_request == 0)return false;
			var sid = "{{ session()->getId() }}";
			var gid = $('input[name="gid"]').val();
			var num = $('input[name="num"]').val();
			var spec_key = $('.spec_key').val();
			var gp = $('input[name="gp"]').val();
			var url = "{{ url('api/common/good/addcart') }}";
			before_request = 0;
			$.post(url,{gid:gid,spec_key:spec_key,num:num,gp:gp,sid:sid,uid:uid},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
	    			// 重新取购物车数量，计算总价
					cartnum(uid);
					$('#myModal').modal('hide');
					$('.alert_good').slideToggle().delay(1500).slideToggle();
				}
				else
				{
					alert(ss.msg);
					$('#myModal').modal('hide');
				}
				before_request = 1;
				return;
			}).error(function() {
				before_request = 1;
				return;
			});
		});


		// 直接购买
		$('.firstorder').on('click',function(event) {
			if(before_request == 0)return false;
			var sid = "{{ session()->getId() }}";
			var gid = $('input[name="gid"]').val();
			var num = $('input[name="num"]').val();
			var spec_key = $('.spec_key').val();
			var gp = $('input[name="gp"]').val();
			var url = "{{ url('api/common/good/addcart') }}";
			before_request = 0;
			$.post(url,{gid:gid,spec_key:spec_key,num:num,gp:gp,sid:sid,uid:uid},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == 1) {
					// 成功以后跳转到购物车页面上
					window.location.href = "{{ url('shop/cart') }}";
				}
				else
				{
					alert(ss.msg);
					$('#myModal').modal('hide');
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
