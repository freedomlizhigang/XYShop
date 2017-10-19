@extends('pc.layout')


@section('title')
    <title>{{ $good->title }}</title>
    <meta name="keywords" content="{{ $good->keyword }}">
    <meta name="description" content="{{ $good->describe }}">
@endsection



<!-- 内容 -->
@section('content')

<!-- catpos -->
<div class="catpos box">
	<a href="#">首页</a> > <a href="#">艺术馆</a> > <span>{{ $good->title }}</span> 
</div>
<!-- end catpos -->
<section class="good_info box clearfix">
	<!-- left ablum -->
	<div class="good_info_left pull-left">
		<div class="good_info_left_ablum">
			<div class="g_i_l_a_top overh">
				@foreach(explode(',',$good->album) as $ga)
				<img src="{{ $ga }}" class="img-responsive" alt="{{ $good->title }}">
				@endforeach
			</div>
			<div class="g_i_l_a_bottom pr">
				<div class="g_i_l_a_btn_left ps iconfont icon-back"></div>
				<div class="g_i_l_a_ablum_thumb ps">
					@foreach(explode(',',$good->album) as $ga)
					<span @if($loop->first) class="active"@endif><img src="{{ $ga }}" class="img-responsive" alt="{{ $good->title }}"></span>
					@endforeach
				</div>
				<div class="g_i_l_a_btn_right ps iconfont icon-right text-right"></div>
			</div>
			<!-- share -->
			<div class="good_share clerafix">
				<a href="#" class="good_share_add_f iconfont icon-favor"><i>加入收藏</i></a>
			</div>
		</div>
	</div>
	<!-- good_right -->
	<div class="good_info_right pull-right">
		<h1 class="g_i_r_t1">{{ $good->title }}</h1>
		<p class="g_i_r_p1 color_main">{{ $good->describe }}</p>
		<div class="g_i_r_prices pr">
			<p class="clearfix"><span class="g_i_r_p_span">商城价</span><span class="g_i_r_p_price">￥<span class="price">{{ $good->shop_price }}</span></span></p>
			<p class="g_i_r_comment ps">
				<i>累计评价</i>
				<em>{{ $good->commentnums }}+</em>
			</p>
			<p class="g_i_r_sale ps">
				<i>累计销量</i>
				<em>{{ $good->sales }}+</em>
			</p>
		</div>
		<p class="g_i_r_ship"><i>配送至</i><em>河北衡水林海雪原区</em></p>
		<div class="g_i_r_spec">
			<!-- 规格开始 -->
			@if(count($filter_spec) > 0)
			@foreach($filter_spec as $ks => $gs)
				<dl class="g_spec clearfix">
					<dt>{{ $ks }}</dt>
					<dd>
						@foreach($gs as $kks => $ggs)
						<a href="javascript:;" onclick="select_filter(this)" @if($kks == 0) class="active"@endif data-item_id="{{ $ggs['item_id'] }}"><input type="radio" name="goods_spec[{{$ks}}]" class="hidden"@if($kks == 0) checked="checked"@endif value="{{ $ggs['item_id'] }}">{{ $ggs['item'] }}</a>
						@endforeach
						<input type="hidden" name="spec_key" class="spec_key" value="">
					</dd>
				</dl>
			@endforeach
			<script>
				$(function(){
					get_goods_price();
				})
                /**
                 * 切换规格
                 */
                function select_filter(obj)
                {
                    $(obj).addClass('active').siblings('a').removeClass('active');
                    $(obj).children('input').prop('checked','checked');
                    $(obj).siblings('a').children('input').attr('checked',false);// 让隐藏的 单选按钮选中
                    // 更新商品价格
                    get_goods_price();
                }
                function get_goods_price()
		        {
		            var price = "{{$good->shop_price}}"; // 商品起始价
		            var store = "{{$good->store}}"; // 商品起始库存
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
		</div>
		<p class="g_i_r_nums">
			<i>数量</i>
			<span class="g_i_r_num_con">
				<span class="num_dec">-</span>
				<span class="num_num">1</span>
				<span class="num_inc">+</span>
			</span>
			<em>剩余库存：<span id="store">{{ $good->store }}</span></em>
		</p>
		<!-- 购物车用的一些表单数据 -->
		<div class="dn">
			<input type="hidden" name="gid" value="{{ $good->id }}">
			<input type="hidden" name="gp" value="{{ $good->shop_price }}">
			<input type="hidden" name="num" class="cartnum" min="1" value="1">
		</div>
		<div class="g_i_r_btns">
			<a href="javascript:;" class="btn_now_mall iconfont icon-light">立即购买</a>
			<a href="javascript:;" class="btn_addcart iconfont icon-cart">加入购物车</a>
		</div>
	</div>
</section>
<!-- 详细内容 -->
<section class="good_con box clearfix">
	<!-- list -->
	<article class="good_left_show overh clearfix pull-left">
		<h3 class="good_con_t3" data-spy="affix">
			<span class="g_c_t_span active">商品介绍</span>
			<span class="g_c_t_span">规格包装</span>
			<span class="g_c_t_span">售后保障</span>
			<span class="g_c_t_span">商品评价({{ $goodcomment->count() }})</span>
		</h3>
		<div class="good_con_text">
			<div class="g_c_t_div g_c_t_content">
				{!! $good->content !!}
			</div>
			<!-- spec -->
			<div class="g_c_t_div g_c_t_spec dn">
				@foreach($good->goodattr as $ga)
					<div class="Ptable-item clearfix">
						<dl>
							<dt>{{ $ga->goodattr->name }}：</dt>
							<dd>
							@if(!is_array($ga->good_attr_value))
							{{ $ga->good_attr_value }}
							@else
							{{ implode(',',$ga->good_attr_value) }}
							@endif
							</dd>
						</dl>
					</div>
				@endforeach
			</div>
			<!-- 售后 -->
			<div class="g_c_t_div g_c_t_sh dn">
                @include('pc.state')
			</div>
			<!-- 评论 -->
			<div class="g_c_t_div g_c_t_comment dn">
				<ul class="list_comment_show clearfix">
					@foreach($goodcomment as $g)
					<li class="clearfix">
						<div class="pull-left l_c_s_left">
							<div class="img-circle comment_img pull-left overh">
							@if($g->user->thumb)
							<img src="{{ $g->user->thumb }}" alt="{{ $g->user->nickname }}">
							@else
							<img src="{{ $sites['static']}}pc/images/shop_logo.png" class="img-circle good_comment_img" alt="{{ $g->user->nickname }}">
							@endif
							</div>
							<p class="comment_user">{{ $g->user->nickname }}</p>
							<p class="comment_group">{{ $g->groupname }}</p>
						</div>
						<div class="pull-left l_c_s_right">
							<p><input class="score_name" value="{{ $g->score }}" type="number" name="data[score]" readonly="readonly" data-size="xs"></p>
							<p>{{ $g->title }}</p>
							<p>{{$g->content}}</p>
							<p class="comment_time">{{ $g->created_at }}</p>
						</div>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	</article>
	<!-- aside -->
	<aside class="ss_m_aside pull-right">
		<h4 class="ss_m_aside_t4">推荐热卖</h4>
		<ul class="ss_m_aside_list mt10">
			<li>
				<a href="#">
					<img src="{{ $sites['static']}}pc/images/8.jpg" class="img-responsive" alt="">
				</a>
				<p class="ss_m_aside_price">¥1059.00</p>
				<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮臂工作超亮</a></h5>
			</li>
			<li>
				<a href="#">
					<img src="{{ $sites['static']}}pc/images/9.jpg" class="img-responsive" alt="">
				</a>
				<p class="ss_m_aside_price">¥1059.00</p>
				<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
			</li>
			<li>
				<a href="#">
					<img src="{{ $sites['static']}}pc/images/10.jpg" class="img-responsive" alt="">
				</a>
				<p class="ss_m_aside_price">¥1059.00</p>
				<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
			</li>
			<li>
				<a href="#">
					<img src="{{ $sites['static']}}pc/images/9.jpg" class="img-responsive" alt="">
				</a>
				<p class="ss_m_aside_price">¥1059.00</p>
				<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
			</li>
			<li>
				<a href="#">
					<img src="{{ $sites['static']}}pc/images/8.jpg" class="img-responsive" alt="">
				</a>
				<p class="ss_m_aside_price">¥1059.00</p>
				<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
			</li>
		</ul>
	</aside>
</section>
<!-- end 商品详情页面内容 -->
<link href="{{ $sites['static']}}pc/css/star-rating.min.css" rel="stylesheet">
<script src="{{ $sites['static']}}pc/js/star-rating.min.js"></script>
<!-- common -->
<script>
	$(function(){
		var topH = $(".good_con").offset().top;
		$('.good_con_t3').affix({
			offset: {
			    top: topH,
			    bottom: function () {
			      return (this.bottom = $('.foot').outerHeight())
			    }
			}
		});
		// 商品内容tabs
		$('.g_c_t_span').on('click',function(){
			var thatIndex = $(this).index();
			$('.g_c_t_span').removeClass('active').eq(thatIndex).addClass('active');
			$('.g_c_t_div').hide().eq(thatIndex).show();
			$('body').animate({scrollTop:topH-20},500);
		});
		// 购物车数量
		var uid = "{{ !is_null(session('member')) ? session('member')->id : 0 }}";
		var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
		// 添加到购物车
		$('.btn_addcart').on('click',function(event) {
			if(before_request == 0)return false;
			var sid = "{{ session()->getId() }}";
			var gid = $('input[name="gid"]').val();
			var num = $('input[name="num"]').val();
			var spec_key = $('.spec_key').val();
			var gp = $('input[name="gp"]').val();
			var url = "{{ url('api/good/addcart') }}";
			before_request = 0;
			$.post(url,{gid:gid,spec_key:spec_key,num:num,gp:gp,sid:sid,uid:uid},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == '1') {
	    			// 重新取购物车数量，计算总价
					cartnum(uid);
					$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
				}
				else
				{
					$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
					if (ss.code == '2') {
						setTimeout(function(){
							window.location.href = "{{ url('user/login') }}";
						},2000);
					}
					// alert(ss.msg);
				}
				before_request = 1;
				return;
			}).error(function() {
				before_request = 1;
				return;
			});
		});


		// 直接购买
		$('.btn_now_mall').on('click',function(event) {
			if(before_request == 0)return false;
			var sid = "{{ session()->getId() }}";
			var gid = $('input[name="gid"]').val();
			var num = $('input[name="num"]').val();
			var spec_key = $('.spec_key').val();
			var gp = $('input[name="gp"]').val();
			var url = "{{ url('api/good/addcart') }}";
			before_request = 0;
			$.post(url,{gid:gid,spec_key:spec_key,num:num,gp:gp,sid:sid,uid:uid},function(d){
				var ss = jQuery.parseJSON(d);
				if (ss.code == '1') {
					// 成功以后跳转到购物车页面上
					$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
					// alert(ss.msg);
					setTimeout(function(){
						window.location.href = "{{ url('shop/cart') }}";
					},2000);
				}
				else
				{
					$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
					// alert(ss.msg);
					if (ss.code == '2') {
						setTimeout(function(){
							window.location.href = "{{ url('user/login') }}";
						},2000);
					}
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
