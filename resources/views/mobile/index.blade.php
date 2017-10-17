@extends('mobile.layout')

@section('content')
	<!-- 搜索 -->
  @component('mobile.common.search',['issub'=>0])
  @endcomponent
	<!-- 首页顶图 -->
	<div class="top_banner overh">
		@foreach(app('tag')->ad(1,5) as $k => $c)
      <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="750px" height="335px" alt="{{ $c->title }}"></a>
    @endforeach
	</div>
	<!-- 分类 -->
	<nav class="menu bgc_f clearfix">
		<ul>
			@foreach(app('tag')->catelist(0,10,0,1) as $c)
			<li>
				<a href="{{ url('/catelist',['id'=>$c->id]) }}" class="menu_img db_ma"><img src="{{ $c->thumb }}" width="70px" height="70px" alt="{{ $c->mobilename }}"></a>
				<a href="{{ url('/catelist',['id'=>$c->id]) }}" class="menu_title">{{ $c->mobilename }}</a>
			</li>
			@endforeach
		</ul>
	</nav>
	<!-- ad -->
	<div class="ads mt20">
		@foreach(app('tag')->ad(2,1) as $k => $c)
      <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="750px" height="190px" alt="{{ $c->title }}"></a>
    @endforeach
	</div>
	<!-- 团 -->
	<section class="bgc_f sec_tuan mt20 clearfix overh">
		<h2 class="t2_tuan">每日<span class="color_shenred">团购</span><span class="t2_span_2">尽享优惠，Top单品</span></h2>
		<ul class="list_tuan clearfix">
			@foreach(app('tag')->tuan(3) as $c)
			<li>
				<a href="{{ url('tuan',['id'=>$c->id]) }}" class="l_t_img db_ma"><img src="{{ $c->good->thumb }}" width="200px" height="200px" alt="{{ $c->title }}"></a>
				<a href="{{ url('tuan',['id'=>$c->id]) }}" class="l_t_title">{{ $c->title }}</a>
				<span class="l_t_price"><em>{{ $c->tuan_num }}人团</em><i>￥{{ $c->price }}</i></span>
				<del class="l_t_oldprice">￥{{ $c->good->shop_price }}</del>
			</li>
			@endforeach
		</ul>
	</section>
	<!-- ad -->
	<div class="ads mt20">
		@foreach(app('tag')->ad(3,1) as $k => $c)
      <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="750px" height="190px" alt="{{ $c->title }}"></a>
    @endforeach
	</div>
	<!-- 循环分类信息 -->
	@foreach(app('tag')->catelist(0,8,1) as $c)
	<section class="mt20 sec_cate clearfix">
		<h2 class="t2_cate pr"><span class="t2_xian ps"></span><span class="t2_cate_span">{{ $c->mobilename }}</span></h2>
		<ul class="list_good clearfix">
			@foreach(app('tag')->good($c->id,8) as $g)
			<li>
				<a href="{{ url('good',['id'=>$g->id]) }}" class="l_g_img"><img src="{{ $g->thumb }}" width="345px" height="345px" alt="{{ $g->title }}"></a>
				<a href="{{ url('good',['id'=>$g->id]) }}" class="l_g_t slh">{{ $g->title }}</a>
				<div class="l_g_info clearfix">
					<span class="l_g_price color_main">￥{{ $g->shop_price }}</span>
					<span class="l_g_btn_addcart iconfont icon-cart"></span>
				</div>
			</li>
			@endforeach
		</ul>
	</section>
	@endforeach
	<!-- 底 -->
  @include('mobile.common.footer')
	<!-- 公用底 -->
	@include('mobile.common.pos_menu')
@endsection