@extends('pc.layout')


@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection

@section('content')
<!-- hots -->
<div class="box hots clearfix overh">
	<div class="pull-left hots_left pr">
		<h4 class="hot_tag ps">热卖<br />推荐</h4>
		<ul class="list_hots clearfix">
			<li>
				<a href="#" class="pull-left hots_img"><img data-original="{{ $sites['static']}}pc/images/img1.png" class="lazy" alt=""></a>
				<div class="pull-right hots_font">
					<h5 class="hots_t5"><a href="#">飞利浦（PHILIPS）50PUF6461/T3 50英寸 流光溢彩 64位九核4K超高清智能液晶平板电视机（黑色）</a></h5>
					<p class="hots_price">￥2799.00</p>
					<a href="#" class="btn btn-xs btn-default hots_link">立即抢购</a>
				</div>
			</li>
			<li>
				<a href="#" class="pull-left hots_img"><img data-original="{{ $sites['static']}}pc/images/img2.png" class="lazy" alt=""></a>
				<div class="pull-right hots_font">
					<h5 class="hots_t5"><a href="#">飞利浦（PHILIPS）50PUF6461/T3 50英寸 流光溢彩 64位九核4K超高清智能液晶平板电视机（黑色）</a></h5>
					<p class="hots_price">￥2799.00</p>
					<a href="#" class="btn btn-xs btn-default hots_link">立即抢购</a>
				</div>
			</li>
			<li>
				<a href="#" class="pull-left hots_img"><img data-original="{{ $sites['static']}}pc/images/img3.png" class="lazy" alt=""></a>
				<div class="pull-right hots_font">
					<h5 class="hots_t5"><a href="#">50英寸 流光溢彩 64位九核4K超高清智能液晶平板电视机（黑色）</a></h5>
					<p class="hots_price">￥2799.00</p>
					<a href="#" class="btn btn-xs btn-default hots_link">立即抢购</a>
				</div>
			</li>
		</ul>
	</div>
	<div class="pull-right hots_right pr">
		<h4 class="hot_tag ps">促销<br />活动</h4>
		<ul class="list_common_1">
			<li><a href="#">·美的清凉秀嗨翻三伏天</a></li>
			<li><a href="#">·每满1000减100</a></li>
			<li><a href="#">·TCL超级品牌日</a></li>
			<li><a href="#">·TCL7·27提前搞大“视”，大惊</a></li>
			<li><a href="#">·搞大”视“创维给你惊喜</a></li>
		</ul>
	</div>
</div>

<!-- crumbs -->
<div class="crumbs_bar box clearfix">
	<div class="crumbs_nav_item one_level">
		<a href="{{ $cinfo->url }}" class="crumbs_link">{{ $cinfo->name }}</a>
		<i class="crumbs_arrow">></i>
	</div>
	<!-- 二级目录 -->
	@if($cate_2_info != '')
	<div class="crumbs_nav_item">
		<div class="crumbs_drop pr">
			<div class="crumbs_drop_title pr">
				<a href="{{ $cate_2_info->url }}" class="crumbs_link">{{ $cate_2_info->name }} <i class="iconfont pull-right icon-unfold"></i></a>
				<i class="crumbs_arrow">></i>
			</div>
			<div class="crumbs_drop_main ps">
				<ul class="crumbs_list">
					@foreach($cate_2 as $c)
					<li><a href="{{ $c->url }}">{{ $c->name }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	@endif
	<!-- 三级目录 -->
	@if($cate_3_info != '')
	<div class="crumbs_nav_item">
		<div class="crumbs_drop pr">
			<div class="crumbs_drop_title pr">
				<a href="{{ $cate_3_info->url }}" class="crumbs_link">{{ $cate_3_info->name }} <i class="iconfont pull-right icon-unfold"></i></a>
				<i class="crumbs_arrow">></i>
			</div>
			<div class="crumbs_drop_main ps">
				<ul class="crumbs_list">
					@foreach($cate_3 as $c)
					<li><a href="{{ $c->url }}">{{ $c->name }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	@endif
	<!-- select -->
	<div class="crumbs_nav_item clearfix">
		@foreach($filter_menu as $m)
		<div class="selector_set pull-left mr5">
			<a href="{{ $m['href'] }}" class="ss_item">
				<b>{{ $m['text_b'] }}：</b>
				<em>{{ $m['text_em'] }}</em>
				<i class="iconfont icon-close"></i>
			</a>
		</div>
		@endforeach
	</div>
	<div class="crumbs_nav_item clear_selected">
		<a href="{{ url('list',['id'=>$cid]) }}">清空筛选</a>
	</div>
</div>
<!-- ss_res -->
<section class="box clearfix ss_wrap">
	<!-- ss_title -->
	<div class="ss_title clearfix overh">
		<h3 class="ss_title_t3"><b>{{ $cinfo->name }}</b><em>商品筛选</em></h3>
		<div class="ss_text">共 {{ $count }} 个商品</div>
	</div>
	<!-- selector start -->
	<!-- 品牌 -->
	@if(count($filterBrand) > 0)
	<div class="selector_line ss_brand clearfix pr">
		<div class="sl_key">
			<span>品牌：</span>
		</div>
		<div class="sl_value">
			<div class="ss_brand_list clearfix">
			@foreach($filterBrand as $v)
			<a href="{{ $v['href'] }}" data-url="{{ $v['href'] }}" data-key="{{ $v['id'] }}" data-val="{{ $v['id'] }}" class="sl_value_a text-center">
				<div class="sl_value_a_img"><img data-original="{{ $v['icon'] }}" class="img-responsive lazy" alt="{{ $v['name'] }}"></div>
				<p class="text-center">{{ $v['name'] }}（{{ $v['describe'] }}）</p>
			</a>
			@endforeach
			</div>
			<div class="sl_value_btn clearfix dn">
				<span class="btn btn-xs btn-success sl_value_btn_true" onclick="submitMoreFilter('brand',this);">确定</span>
				<span class="btn btn-xs btn-default sl_value_btn_cancel">取消</span>
			</div>
		</div>
		<div class="sl_ext ps">
			<a href="javascript:;" class="sl_ext_more"><i class="iconfont icon-add"></i>多选</a>
		</div>
	</div>
	@endif

	<!-- 规格 -->
	@if(count($filterSpec) > 0)
	@foreach($filterSpec as $v)
	<div class="selector_line ss_fold clearfix pr">
		<div class="sl_key">
			<span>{{ $v['name'] }}</span>
		</div>
		<div class="sl_value clearfix">
			@foreach($v['goodspecitem'] as $vv)
			<a href="{{ $vv['href'] }}" data-url="{{ $vv['href'] }}" data-key="{{ $vv['id'] }}" data-val="{{ $vv['id'] }}" class="sl_value_a">{{ $vv['item'] }}</a>
			@endforeach
			<div class="sl_value_btn dn">
				<span class="btn btn-xs btn-success sl_value_btn_true" onclick="submitMoreFilter('spec',this);">确定</span>
				<span class="btn btn-xs btn-default sl_value_btn_cancel">取消</span>
			</div>
		</div>
		<div class="sl_ext ps">
			<a href="javascript:;" class="sl_ext_more"><i class="iconfont icon-add"></i>多选</a>
		</div>
	</div>
	@endforeach
	@endif
	@if(count($filterPrice) > 0)
	<!-- 价格 -->
	<div class="selector_line ss_fold clearfix pr">
		<div class="sl_key">
			<span>价格</span>
		</div>
		<div class="sl_value clearfix">
			@foreach($filterPrice as $v)
			<a href="{{ $v['href'] }}">{{ $v['value'] }}</a>
			@endforeach
		</div>
	</div>
	@endif
	<!-- 属性 -->
	@if(count($filterAttr) > 0)
	@foreach($filterAttr as $v)
	<div class="selector_line ss_fold clearfix pr">
		<div class="sl_key">
			<span>{{ $v['name'] }}</span>
		</div>
		<div class="sl_value clearfix">
			@foreach($v['url'] as $vv)
			<a href="{{ $vv['href'] }}" data-url="{{ $vv['href'] }}" data-key="{{ $vv['key'] }}" data-val="{{ $vv['val'] }}" class="sl_value_a">{{ $vv['val'] }}</a>
			@endforeach
			<div class="sl_value_btn dn">
				<span class="btn btn-xs btn-success sl_value_btn_true" onclick="submitMoreFilter('attr',this);">确定</span>
				<span class="btn btn-xs btn-default sl_value_btn_cancel">取消</span>
			</div>
		</div>
		<div class="sl_ext ps">
			<a href="javascript:;" class="sl_ext_more"><i class="iconfont icon-add"></i>多选</a>
		</div>
	</div>
	@endforeach
	@endif

	<!-- selector end -->
	<!-- selector main -->
	<div class="ss_main clearfix">
		<!-- list -->
		<div class="ss_m_list clearfix pull-left">
			<!-- ss_sort -->
			<div class="ss_sort clearfix">
				<div class="ss_sort_left pull-left">
					<a href="{{ urldecode(url("list/$cid").'?'.http_build_query(array_merge($filter_param,['sort'=>'sort']))) }}" class="ss_sort_all @if($sort == 'sort' || $sort == '') active @endif">综合</a>
					<a href="{{ urldecode(url("list/$cid").'?'.http_build_query(array_merge($filter_param,['sort'=>'sale']))) }}" class="ss_sort_sale @if($sort == 'sale') active @endif">销量</a>
					
					@if($sort_asc == 'desc')
					<a href="{{ urldecode(url("list/$cid").'?'.http_build_query(array_merge($filter_param,['sort'=>'price','sort_asc'=>'asc']))) }}" class="clearfix ss_sortprice @if($sort == 'price') active @endif">价格<i class="iconfont icon-order pull-right"></i></a>
					@else
					<a href="{{ urldecode(url("list/$cid").'?'.http_build_query(array_merge($filter_param,['sort'=>'price','sort_asc'=>'desc']))) }}" class="clearfix ss_sortprice @if($sort == 'price') active @endif">价格<i class="iconfont icon-order pull-right"></i></a>
					@endif

					<a href="{{ urldecode(url("list/$cid").'?'.http_build_query(array_merge($filter_param,['sort'=>'comment']))) }}" class="ss_sort_comments @if($sort == 'comment') active @endif">评论数</a>
					<a href="{{ urldecode(url("list/$cid").'?'.http_build_query(array_merge($filter_param,['sort'=>'times']))) }}" class="ss_sort_time @if($sort == 'times') active @endif">上架时间</a>
				</div>
				<div class="ss_sort_page pull-right">
					{!! $list->appends($filter_param)->links('pc.pages') !!}
				</div>
			</div>
			<!-- ul.wrap -->
			<div class="good_list_1 clearfix">
				<ul class="gl_wrap clearfix">
					@foreach($list as $l)
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="{{ $l->url }}" class="gl_wp_item_img center-block overh"><img data-original="{{ $l->thumb }}" width="300" height="300" class="img-responsive center-block lazy" alt="{{ $l->title }}"></a>
							<p class="gl_item_price"><em>￥</em><i>{{ $l->shop_price }}</i></p>
							<p class="gl_item_title">
								<a href="{{ $l->url }}">
									<em>{{ $l->title }}</em>
									<!-- <i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i> -->
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="{{ $l->url }}" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="{{ $l->url }}" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					@endforeach
				</ul>
				<div class="pages pull-right">
					{!! $list->appends($filter_param)->links() !!}
				</div>
			</div>
		</div>
		<!-- aside -->
		<div class="ss_m_aside pull-right">
			<h4 class="ss_m_aside_t4">推荐热卖</h4>
			<ul class="ss_m_aside_list mt10">
				<li>
					<a href="#">
						<img data-original="{{ $sites['static']}}pc/images/8.jpg" class="lazy img-responsive center-block" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img data-original="{{ $sites['static']}}pc/images/9.jpg" class="lazy img-responsive center-block" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img data-original="{{ $sites['static']}}pc/images/10.jpg" class="lazy img-responsive center-block" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img data-original="{{ $sites['static']}}pc/images/9.jpg" class="lazy img-responsive center-block" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img data-original="{{ $sites['static']}}pc/images/8.jpg" class="lazy img-responsive center-block" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
				</li>
			</ul>
		</div>
	</div>
	<!-- selector main end -->
</section>

<!-- end 列表页面内容 -->

<!-- 筛选功能 js -->
<script>
	$(function(){
		// 点多选按钮
		$('.sl_ext_more').click(function() {
			var that = $(this).parent('.sl_ext').siblings('.sl_value');
			// 关闭其它的，同时取消选择状态
			$('.sl_value_btn').hide(function(){
				var that = $(this).parent('.sl_value');
				that.children('a').each(function(index, el) {
					var thatA = $(this);
					thatA.attr('href',thatA.attr('data-url'));
				});
				// 取消点击事件
				that.find('.sl_value_a').off('click');
			});
			// 打开当前的并把属性转换成javascript:;
			that.children('.sl_value_btn').show(function(){
				var thatTrue = $(this).children('.sl_value_btn_true');
				var that = $(this).parent('.sl_value');
				that.find('.sl_value_a').each(function(index, el) {
					var thatA = $(this);
					thatA.attr('href','javascript:;');
				});
				// 链接绑定点击事件
				that.find('.sl_value_a').on('click',function(){
					var that = $(this);
					if (that.hasClass('sl_value_active')) {
						that.removeClass('sl_value_active');
					}
					else
					{
						that.addClass('sl_value_active');
					}
				});
			});
		});
		// 点取消按钮
		$('.sl_value_btn_cancel').click(function() {
			var that = $(this).parent('.sl_value_btn');
			that.hide();
			var thatP = that.parent('.sl_value');
			thatP.children('a').each(function(index, el) {
				var thatA = $(this);
				thatA.attr('href',thatA.attr('data-url'));
			});
		});
	});
    //############   点击多选确定按钮      ############
	// t 为类型  是品牌 还是 规格 还是 属性
	// btn 是点击的确定按钮用于找位置
	var get_parment = {!! json_encode($_GET) !!};
	function submitMoreFilter(t,btn)
	{
		var that = $(btn).parent('.sl_value_btn');
	    // 没有被勾选的时候
	    if(!that.parent('.sl_value').find('.sl_value_active').hasClass("sl_value_active")){
	    	alert('没有被勾选的');
	        return false;
	    }

	    // 获取现有的get参数
	    var key = ''; // 请求的参数名称
	    var val = new Array(); // 请求的参数值
	    that.parent('.sl_value').find('.sl_value_active').each(function(i,o){
	        key = $(o).data('key');
	        val.push($(o).data('val'));
	    });
	    // parment = key+'_'+val.join('_');
		// return false;
	    // 品牌
	    if(t == 'brand')
	    {
	        get_parment.brand_id = val.join('@');
	    }
	    // 规格
	    if(t == 'spec')
	    {
	        if(get_parment.hasOwnProperty('spec'))
	        {
	            get_parment.spec += '@'+val.join('@');
	        }
	        else
	        {
	            get_parment.spec = val.join('@');
	        }
	    }
	    // 属性
	    if(t == 'attr')
	    {
	        if(get_parment.hasOwnProperty('attr'))
	        {
	            get_parment.attr += '@'+key+'_'+val.join('_');
	        }
	        else
	        {
	            get_parment.attr = key+'_'+val.join('_');
	        }
	    }
	    // 组装请求的url
	    var url = '';
	    for ( var k in get_parment )
	    {
	        url += "&"+k+'='+get_parment[k];
	    }
	    console.log('get_parment',get_parment);
	    location.href ="/list/{{$cid}}?"+url;
	}
</script>
@endsection