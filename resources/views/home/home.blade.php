@extends('home.layout')

@section('title')
    <title>{{ $info->title }}</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection


@section('content')

    <header class="head clearfix container-fluid">
    	<div class="row">
	        <div id="carousel" class="carousel slide" data-ride="carousel">
	            <!-- Indicators -->
	            <ol class="carousel-indicators">
	            @foreach(app('tag')->ad(2,6) as $k => $c)
	            <li data-target="#carousel" data-slide-to="{{ $k }}" class="@if($k == 0) active @endif"></li>
	            @endforeach
	            </ol>

	            <!-- Wrapper for slides -->
	            <div class="carousel-inner" role="listbox">
	            @foreach(app('tag')->ad(2,6) as $k => $c)
	            <div class="item @if($k == 0) active @endif">
	              <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" alt="{{ $c->title }}"></a>
	            </div>
	            @endforeach
	            
	            </div>
	            <!-- Controls -->
	            <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
	            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	            <span class="sr-only">Previous</span>
	            </a>
	            <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
	            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	            <span class="sr-only">Next</span>
	            </a>
	        </div>
	        <script>
	            $(function(){
	            	$("#carousel").carousel();
	            });
	        </script>
        </div>
    </header>
	
	<!-- 搜索 -->
	<section class="search container-fluid overh">
		<form action="{{ url('search') }}" class="form-inline mt10">
			<div class="row">
				<div class="col-xs-9">
					<div class="form-group">
						<input type="text" name="q" class="form-control" placeholder="搜索...">
					</div>
				</div>
				<div class="col-xs-3">
					<button type="submit" class="btn btn-success">搜索</button>
				</div>
			</div>
		</form>
	</section>
	<!-- 主分类 -->
	<section class="container-fluid mt10 goodcate">
		@foreach(app('tag')->goodcate(0,8) as $c)
		<div class="col-xs-3 mt10">
			<a href="{{ url('/shop/goodlist',['id'=>$c->id]) }}" class="goodcate_img"><img src="{{ $c->thumb }}" alt="{{ $c->name }}" class="img-responsive"></a>
			<a href="{{ url('/shop/goodlist',['id'=>$c->id]) }}" class="mt5 db">{{ str_limit($c->name,10,'') }}</a>
		</div>
		@endforeach
	</section>

	<!-- 固定位广告 -->
	<div class="container-fluid cate_list">
		<h2 class="h_t"><img src="{{ $sites['static']}}home/images/t_1.png" class="img-responsive" alt=""></h2>
		<div class="row ad_pos">
			@foreach(app('tag')->ad(1,1) as $k => $c)
            <div class="col-xs-5">
            	<a href="{{ $c->url }}"><img src="{{ $c->thumb }}" alt="{{ $c->title }}" class="img-responsive"></a>
            </div>
            @endforeach
            @foreach(app('tag')->ad(3,2) as $k => $c)
            <div class="col-xs-7 @if($k == 1) mt5 @endif">
            	<a href="{{ $c->url }}"><img src="{{ $c->thumb }}" alt="{{ $c->title }}" class="img-responsive"></a>
            </div>
            @endforeach

		</div>
	</div>
	<!-- 活动 -->
	@if(app('tag')->hd(5)->count() > 0)
	<div class="container-fluid mt10">
		<h2 class="h_t mb10"><a href="{{ url('shop/hd/index') }}"><img src="{{ $sites['static']}}home/images/hd_t.png" class="img-responsive" alt=""></a></h2>
		@foreach(app('tag')->hd(5) as $l)
		<div class="hd_list_div pr">
			<a href="{{ url('/shop/hd/list',['id'=>$l->id]) }}"><img src="{{ $l->thumb }}" class="img-responsive" alt=""></a>
			<div class="hd_info clearfix">
				<h4 class="hd_title text-nowarp"><a class="text-success" href="{{ url('/shop/hd/list',['id'=>$l->id]) }}">{{ $l->title }}</a></h4>
				<p class="times">活动时间：{{ str_limit($l->starttime,10,'') }} 至 {{ str_limit($l->endtime,10,'') }}</p>
			</div>
		</div>
		@endforeach
	</div>
	@endif
	<!-- 团 -->
	@if(app('tag')->tuan(6)->count() > 0)
	<div class="container-fluid cate_list">
		<h2 class="h_t"><img src="{{ $sites['static']}}home/images/tuan_t.png" class="img-responsive" alt=""></h2>
		<div class="row good_list">
			@foreach(app('tag')->tuan(6) as $l)
			<div class="col-xs-6 pr">
				<!-- 如果有标签，加标签 -->
				@if($l->good->tags != '')
				<div class="ps good_tag">
					{{ $l->good->tags }}
				</div>
				@endif

				<a href="{{ url('/shop/tuan',['tid'=>$l->id,'gid'=>$l->good_id]) }}" class="good_thumb"><img src="{{ $l->good->thumb }}" class="img-responsive" alt=""></a>
				<div class="good_info clearfix">
					<h4 class="good_title text-nowarp">
					@if($l->good->isxs)
					<span class="tags">限时</span>
					@endif
					@if($l->good->isxl)
					<span class="tags">限量</span>
					@endif
					<a href="{{ url('/shop/tuan',['tid'=>$l->id,'gid'=>$l->good_id]) }}">{{ $l->title }}</a></h4>
					<div class="row">
						<div class="col-xs-9">
							<p class="good_pric">会员价：<del class="good_pric_span">￥{{ $l->good->price }}</del></p>
							<p class="good_pric">团购价：<strong class="good_pric_span color_2">￥{{ $l->prices }}</strong></p>
						</div>
						<div class="col-xs-3 lh3">
							<a href="{{ url('/shop/tuan',['tid'=>$l->id,'gid'=>$l->good_id]) }}" class="glyphicon glyphicon-shopping-cart addcart">
							</a>
						</div>
					</div>
					<div class="text-warning fz12 good_tuan">开团至：{{ str_limit($l->endtime,10,'') }}</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif
	<!-- 分类里的 -->
	@foreach($cates as $c)
	<div class="container-fluid cate_list">
		<div class="row good_list">
			@foreach(app('tag')->good($c->id,6) as $l)
			<div class="col-xs-6 pr">
				@if($l->tags != '')
				<div class="ps good_tag">
					{{ $l->tags }}
				</div>
				@endif
				<a href="{{ url('/shop/good',['id'=>$l->id]) }}" class="good_thumb"><img src="{{ $l->thumb }}" class="img-responsive" alt=""></a>
				<div class="good_info clearfix">
					<h4 class="good_title text-nowarp">
					@if($l->isxs)
					<span class="tags">限时</span>
					@endif
					@if($l->isxl)
					<span class="tags">限量</span>
					@endif
					<a href="{{ url('/shop/good',['id'=>$l->id]) }}">{{ $l->title }}</a></h4>
					<div class="row">
						<div class="col-xs-9">
							<p class="good_pric">会员价：<strong class="good_pric_span color_2">￥{{ $l->price }}</strong></p>
						</div>
						<div class="col-xs-3 lh2">
							<a href="{{ url('/shop/good',['id'=>$l->id]) }}" class="glyphicon glyphicon-shopping-cart addcart">
							</a>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<a href="{{ url('/shop/goodlist',['id'=>$c->id]) }}" class="home_more mb10 text-center mt10 btn btn-default center-block">查看更多 >></a>
	</div>
	@endforeach
	
	
@include('home.foot')
@endsection