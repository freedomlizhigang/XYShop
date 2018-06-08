@extends('mobile.layout')

@section('content')
	<!-- 搜索 -->
  @component('mobile.common.search',['issub'=>0])
  @endcomponent
	<!-- 首页顶图 -->
	<div class="top_banner overh">
		@php $indexAd = app('tag')->ad(1,5); @endphp
    <div class="touchslider touchslider-shop pr">
      <div class="touchslider-viewport pr">
        <div style="width: 10000px;">
        @foreach($indexAd as $k => $c)
        <div class="touchslider-item"><a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="750" height="335" alt="{{ $c->title }}"></a></div>
        @endforeach
        </div>
      </div>
      <div class="touchslider-nav ps">
        @foreach($indexAd as $k => $c)
        <a class="touchslider-nav-item @if($loop->first) touchslider-nav-item-current @endif"></a>
        @endforeach
      </div>
    </div>
    <script>
      $(function(){
        $(".touchslider-shop").touchSlider({mouseTouch: true,autoplay:true,delay:3500});
      })
    </script>
	</div>
	<!-- 分类 -->
	<nav class="menu bgc_f clearfix">
		<ul>
			@foreach(app('tag')->catelist(0,10,0,1) as $c)
			<li>
				<a href="{{ $c->url }}" class="menu_img db_ma"><img data-original="{{ $c->thumb }}" width="70" height="70" alt="{{ $c->mobilename }}" class="lazy"></a>
				<a href="{{ $c->url }}" class="menu_title">{{ $c->mobilename }}</a>
			</li>
			@endforeach
		</ul>
	</nav>
	<!-- ad -->
	<div class="ads mt20">
		@foreach(app('tag')->ad(2,1) as $k => $c)
    <a href="{{ $c->url }}"><img data-original="{{ $c->thumb }}" width="750" height="190" alt="{{ $c->title }}" class="lazy"></a>
    @endforeach
	</div>
	<!-- 团 -->
	<section class="bgc_f sec_tuan mt20 clearfix overh">
		<h2 class="t2_tuan">每日<span class="color_shenred">团购</span><span class="t2_span_2">尽享优惠，Top单品</span></h2>
		<ul class="list_tuan clearfix">
			@foreach(app('tag')->tuan(3) as $c)
			<li>
				<a href="{{ url('tuan',['id'=>$c->good_id]) }}" class="l_t_img db_ma"><img data-original="{{ $c->good->thumb }}" width="200" height="200" alt="{{ $c->title }}" class="lazy"></a>
				<a href="{{ url('tuan',['id'=>$c->good_id]) }}" class="l_t_title slh">{{ $c->title }}</a>
				<span class="l_t_price"><em>{{ $c->tuan_num }}人团</em><i>￥{{ $c->price }}</i></span>
				<del class="l_t_oldprice">￥{{ $c->good->shop_price }}</del>
			</li>
			@endforeach
		</ul>
	</section>
  <!-- 抢购活动 -->
  <section class="bgc_f sec_tuan mt20 clearfix overh">
    <h2 class="t2_tuan">全民<span class="color_shenred">抢购</span><span class="t2_span_2">尽享优惠，Top单品</span></h2>
    <ul class="list_tuan clearfix">
      @foreach(app('tag')->timetobuy(3) as $c)
      <li>
        <a href="{{ url('timetobuy',['id'=>$c->good_id]) }}" class="l_t_img db_ma"><img data-original="{{ $c->good->thumb }}" width="200" height="200" alt="{{ $c->title }}" class="lazy"></a>
        <a href="{{ url('timetobuy',['id'=>$c->good_id]) }}" class="l_t_title slh">{{ $c->title }}</a>
        <span class="l_t_price"><em>{{ $c->buy_num }}人团</em><i>￥{{ $c->price }}</i></span>
        <del class="l_t_oldprice">￥{{ $c->good->shop_price }}</del>
      </li>
      @endforeach
    </ul>
  </section>
	<!-- ad -->
	<div class="ads mt20">
		@foreach(app('tag')->ad(3,1) as $k => $c)
      <a href="{{ $c->url }}"><img data-original="{{ $c->thumb }}" width="750" height="190" alt="{{ $c->title }}" class="lazy"></a>
    @endforeach
	</div>
	<!-- 循环分类信息 -->
	@foreach(app('tag')->catelist(0,8,1) as $c)
	<section class="mt20 sec_cate clearfix">
		<h2 class="t2_cate pr"><span class="t2_xian ps"></span><span class="t2_cate_span">{{ $c->mobilename }}</span></h2>
		<ul class="list_good clearfix">
    		@foreach(app('tag')->good($c->arrchildid,8) as $g)
    		<li>
    			<a href="{{ $g->url }}" class="l_g_img"><img data-original="{{ $g->thumb }}" width="345" height="345" alt="{{ $g->title }}" class="lazy"></a>
    			<a href="{{ $g->url }}" class="l_g_t slh">
                @if($g->prom_tag != '')
                <i class="label label-red">{{ $g->prom_tag }}</i>
                @endif
                @if($g->new_tag != '')
                <i class="label label-red">{{ $g->new_tag }}</i>
                @endif
                @if($g->pos_tag != '')
                <i class="label label-red">{{ $g->pos_tag }}</i>
                @endif
                @if($g->hot_tag != '')
                <i class="label label-red">{{ $g->hot_tag }}</i>
                @endif
                {{ $g->title }}</a>
				<div class="l_g_info clearfix">
					<span class="l_g_price color_main">￥{{ $g->shop_price }}</span>
					<a class="l_g_btn_addcart iconfont icon-cart" href="{{ $g->url }}"></a>
				</div>
			</li>
			@endforeach
		</ul>
	</section>
	@endforeach
    <!-- 分享 -->
    @include('mobile.common.share')
@endsection