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
		<!-- 团购信息 -->
		<div class="bgf pd10 mt10 tuan_info text-left">
				<p>从 <span class="tuan_times">{{ str_limit($tuan->starttime,10,'') }}</span> 至 <span class="tuan_times">{{ str_limit($tuan->endtime,10,'') }}</span> 结束</p>
				<p class="mt5">最少参团：<span class="tuan_times">{{ $tuan->nums }}</span> 人  已参加：<span class="tuan_times">{{ $tuan->havnums }}</span> 人</p>
		</div>

		<div class="good_bgf">
			<div class="good_show">
				<h1 class="good_show_title"><a href="{{ url('shop/good',['id'=>$info->id]) }}">{{ $info->title }}</a></h1>
				<!-- <h4>{{ $info->pronums }}</h4> -->
				<form action="{{ url('shop/tuan/addorder') }}" class="form_addcart">
					{{ csrf_field() }}
					<!-- 价格、库存，购物车 -->
					<input type="hidden" value="{{ $tuan->prices }}" name="gp">
					<input type="hidden" value="{{ $tuan->id }}" name="tid">

					<!-- 规格开始 -->
					@if(count($filter_spec) > 0)
					<table class="table">
					@foreach($filter_spec as $ks => $gs)
						<tr>
							<td width="80" class="text-right">{{ $ks }}：</td>
							<td>
								@foreach($gs as $kks => $ggs)
								<span onclick="select_filter(this);" class="label label-default @if($kks == 0) label-success @endif spec_item" data-item_id="{{ $ggs['item_id'] }}"><input type="radio" name="goods_spec[{{$ks}}]" class="hidden"@if($kks == 0) checked="checked"@endif value="{{ $ggs['item_id'] }}">{{ $ggs['item'] }}</span>
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
				            $("del.price").html(price); // 变动价格显示
				            // $("input[name='gp']").val(price);
				        }
				        /***用作 sort 排序用*/
				        function sortNumber(a,b)
				        {
				            return a - b;
				        }
	                </script>
					@endif


					<div class="row price_store mt10">
						<div class="col-xs-6">价格：￥<del class="price color_l">{{ $info->price }}</del></div>
						<div class="col-xs-6">团购格：￥<span class="price color_l">{{ $tuan->prices }}</span></div>
						<div class="col-xs-6 text-left mt store">库存：{{ $tuan->store }}</div>
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
					<!-- 加购物车 -->
					<input type="hidden" name="aid" class="aid" value="0">
					<input type="hidden" name="ziti" class="ziti" value="0">
					<input type="hidden" name="tt" value="{{ microtime(true) }}">
					<input type="hidden" value="{{ $info->id }}" name="gid">
					<input type="hidden" min="0" value="1" class="form-control cartnum" name="num">
				</form>
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
	@if($info->notice != '')
	<div class="good_show_con mt10">
		{!! $info->notice !!}
	</div>
	@endif
	@if($info->pack != '')
	<div class="good_show_con mt10">
		<h3 class="h3_cate"><span class="h3_cate_span">规格包装</span></h3>
		{!! $info->pack !!}
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
	<botton class="alert_addcart alert_addcart_tuan tuan_addcart"@if(session()->has('member')) data-login="1"@else data-login="0"@endif>立即参团</botton>
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
<!-- 加载中 -->
<div class="pos_bg">
	<div class="pos_text">提交中，请稍候...</div>
</div>
<script>
	// 购物车数量
	var uid = "{{ !is_null(session('member')) ? session('member')->id : 0 }}";
	var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
	$(function(){
		// 购物车数量
		cartnum({{ session('member')->id }});
		
		$('.aid').val($('.addressid:checked').val());

		$('.addressid').change(function() {
			var aid = $(this).val();
			$('.aid').val(aid);
			// 提交订单
			addTuanOrder();
		});

		$('.zitiid').change(function() {
			var aid = $(this).val();
			$('.ziti').val(aid);
			$('.aid').val(0);
			// 提交订单
			addTuanOrder();
		});

		// 添加到团购
		$('.tuan_addcart').click(function(event) {
			// 判断登陆
			if ($(this).attr('data-login') == 0) {
				alert('请先登陆！');
				window.location.href = host + "user/login";
				return;
			}
			$('#myModal').modal('show');
		});
	});
	// 提交团购订单
	function addTuanOrder()
	{
		$('#myModal').modal('hide');
		if(before_request == 0)return false;
		$('.pos_bg').show();
    	var aid = $('.aid').val();
    	var ziti = $('.ziti').val();
    	var gid = $('input[name="gid"]').val();
    	var tid = $('input[name="tid"]').val();
    	var spec_key = $('input[name="spec_key"]').val();
    	var num = $('.cartnum').val();
    	var gp = $('input[name="gp"]').val();
    	before_request = 0;
		$.post( host +'api/common/good/addtuanss',{uid:uid,aid:aid,ziti:ziti,gid:gid,tid:tid,spec_key:spec_key,num:num,gp:gp},function(d){
			var ss = jQuery.parseJSON(d);
			console.log(ss);
			if (ss.code == 1) {
    			// 跳转到订单页面
    			window.location.href = "{{ url('shop/addorder') }}" + '/' + ss.msg;
			}
			else
			{
				// console.log(d);
				alert(ss.msg);
				$('.pos_bg').hide();
			}
			ss = null;
			before_request = 1;
			return;
		}).error(function() {
			$('.pos_bg').hide();
			before_request = 1;
			return;
		});
	}
</script>
@endsection
