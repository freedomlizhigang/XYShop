@extends('home.layout')


@section('content')

<!-- hots -->
<div class="box hots clearfix overh">
	<div class="pull-left hots_left pr">
		<h4 class="hot_tag ps">热卖<br />推荐</h4>
		<ul class="list_hots clearfix">
			<li>
				<a href="#" class="pull-left hots_img"><img src="{{ $sites['static']}}home/images/img1.png" alt=""></a>
				<div class="pull-right hots_font">
					<h5 class="hots_t5"><a href="#">飞利浦（PHILIPS）50PUF6461/T3 50英寸 流光溢彩 64位九核4K超高清智能液晶平板电视机（黑色）</a></h5>
					<p class="hots_price">￥2799.00</p>
					<a href="#" class="btn btn-xs btn-default hots_link">立即抢购</a>
				</div>
			</li>
			<li>
				<a href="#" class="pull-left hots_img"><img src="{{ $sites['static']}}home/images/img2.png" alt=""></a>
				<div class="pull-right hots_font">
					<h5 class="hots_t5"><a href="#">飞利浦（PHILIPS）50PUF6461/T3 50英寸 流光溢彩 64位九核4K超高清智能液晶平板电视机（黑色）</a></h5>
					<p class="hots_price">￥2799.00</p>
					<a href="#" class="btn btn-xs btn-default hots_link">立即抢购</a>
				</div>
			</li>
			<li>
				<a href="#" class="pull-left hots_img"><img src="{{ $sites['static']}}home/images/img3.png" alt=""></a>
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
		<a href="#" class="crumbs_link">家用电器</a>
		<i class="crumbs_arrow">></i>
	</div>
	<div class="crumbs_nav_item">
		<div class="crumbs_drop pr">
			<div class="crumbs_drop_title pr">
				<a href="#" class="crumbs_link">大家电 <i class="iconfont pull-right icon-unfold"></i></a>
				<i class="crumbs_arrow">></i>
			</div>
			<div class="crumbs_drop_main ps">
				<ul class="crumbs_list">
					<li><a href="#">厨卫大电</a></li>
					<li><a href="#">大 家 电</a></li>
					<li><a href="#">厨房小电</a></li>
					<li><a href="#">生活电器</a></li>
					<li><a href="#">个护健康</a></li>
					<li><a href="#">商用电器</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="crumbs_nav_item">
		<div class="crumbs_drop pr">
			<div class="crumbs_drop_title pr">
				<a href="#" class="crumbs_link">平板电视 <i class="iconfont pull-right icon-unfold"></i></a>
				<i class="crumbs_arrow">></i>
			</div>
			<div class="crumbs_drop_main ps">
				<ul class="crumbs_list">
					<li><a href="#">平板电视</a></li>
					<li><a href="#">空调</a></li>
					<li><a href="#">中央空调</a></li>
					<li><a href="#">冰箱</a></li>
					<li><a href="#">洗衣机</a></li>
					<li><a href="#">家庭影院</a></li>
					<li><a href="#">DVD/电视盒子</a></li>
					<li><a href="#">迷你音响</a></li>
					<li><a href="#">冷柜/冰吧</a></li>
					<li><a href="#">家电配件</a></li>
					<li><a href="#">功放</a></li>
					<li><a href="#">回音壁/Soundbar</a></li>
					<li><a href="#">Hi-Fi专区</a></li>
					<li><a href="#">电视盒子</a></li>
					<li><a href="#">酒柜</a></li>
				</ul>
			</div>
		</div>

	</div>
	<!-- select -->
	<div class="crumbs_nav_item">
		<div class="selector_set">
			<a href="#" class="ss_item">
				<b>电视类型：</b>
				<em>曲面电视</em>
				<i class="iconfont icon-close"></i>
			</a>
		</div>
	</div>
	<div class="crumbs_nav_item clear_selected">
		<a href="#">清空筛选</a>
	</div>
</div>
<!-- ss_res -->
<section class="box clearfix ss_wrap">
	<!-- ss_title -->
	<div class="ss_title clearfix overh">
		<h3 class="ss_title_t3"><b>平板电视</b><em>商品筛选</em></h3>
		<div class="ss_text">共 43 个商品</div>
	</div>
	<!-- selector start -->
	<!-- 品牌 -->
	@if(count($filterBrand) > 0)
	<div class="selector_line ss_brand clearfix pr">
		<div class="sl_key">
			<span>品牌：</span>
		</div>
		<div class="sl_value">
			<ul class="ss_brand_list clearfix">
			@foreach($filterBrand as $v)
				<li>
					<a href="{{ $v['href'] }}">
						<img src="{{ $v['icon'] }}" class="img-responsive" alt="{{ $v['name'] }}">
						{{ $v['name'] }}（{{ $v['describe'] }}）
					</a>
				</li>
			@endforeach
			</ul>
		</div>
		<div class="sl_ext ps">
			<a href="#" class="sl_ext_more"><i class="iconfont icon-add"></i>多选</a>
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
			<a href="{{ $vv['href'] }}">{{ $vv['item'] }}</a>
			@endforeach
		</div>
		<div class="sl_ext ps">
			<a href="#" class="sl_ext_more"><i class="iconfont icon-add"></i>多选</a>
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
		<div class="sl_ext ps">
			<a href="#" class="sl_ext_more"><i class="iconfont icon-add"></i>多选</a>
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
			<a href="{{ $vv['href'] }}">{{ $vv['val'] }}</a>
			@endforeach
		</div>
		<div class="sl_ext ps">
			<a href="#" class="sl_ext_more"><i class="iconfont icon-add"></i>多选</a>
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
					<a href="#" class="ss_sort_all active">综合</a>
					<a href="#" class="ss_sort_sale">销量</a>
					<a href="#" class="clearfix ss_sortprice">价格<i class="iconfont icon-order pull-right"></i></a>
					<a href="#" class="ss_sort_comments">评论数</a>
					<a href="#" class="ss_sort_time">上架时间</a>
				</div>
				<div class="ss_sort_page pull-right">
					
				</div>
			</div>
			<!-- ul.wrap -->
			<div class="good_list_1 clearfix">
				<ul class="gl_wrap clearfix">
					<li class="gl_wp_item active">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/1.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/2.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/3.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/4.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/5.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/6.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/7.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
					<li class="gl_wp_item">
						<div class="gl_wp_wrap">
							<a href="#" class="center-block"><img src="{{ $sites['static']}}home/images/3.jpg" class="img-responsive center-block" alt=""></a>
							<p class="gl_item_price"><em>￥</em><i>2899.00</i></p>
							<p class="gl_item_title">
								<a href="#">
									<em>飞利浦（PHILIPS）55PUF6092/T3 55英寸 64位九核4K超高清智能液晶平板电视机（银灰色京东微联APP控制）</em>
									<i>6期免息！64位4K芯片 腾讯企鹅TV ！二级能效更节能！价保30天！【更多活动点击查看】</i>
								</a>
							</p>
							<p class="gl_item_btns row btn-group">
								<a href="#" class="btn btn-xs btn-default col-xs-6"><i class="iconfont icon-like"></i>关注</a>
								<a href="#" class="btn btn-xs btn-default col-xs-6 color_vice"><i class="iconfont icon-cart_light"></i>加入购物车</a>
							</p>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<!-- aside -->
		<div class="ss_m_aside pull-right">
			<h4 class="ss_m_aside_t4">推荐热卖</h4>
			<ul class="ss_m_aside_list mt10">
				<li>
					<a href="#">
						<img src="{{ $sites['static']}}home/images/8.jpg" class="img-responsive" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img src="{{ $sites['static']}}home/images/9.jpg" class="img-responsive" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img src="{{ $sites['static']}}home/images/10.jpg" class="img-responsive" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img src="{{ $sites['static']}}home/images/9.jpg" class="img-responsive" alt="">
					</a>
					<p class="ss_m_aside_price">¥1059.00</p>
					<h5 class="ss_m_aside_t5"><a href="#">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</a></h5>
				</li>
				<li>
					<a href="#">
						<img src="{{ $sites['static']}}home/images/8.jpg" class="img-responsive" alt="">
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

@endsection