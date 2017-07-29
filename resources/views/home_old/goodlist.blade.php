@extends('home.layout')

@section('title')
    <title>@if($info->seotitle != ''){{ $info->seotitle }}@else{{ $info->name }}@endif</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection


@section('content')

	<!-- 搜索 -->
<!-- 	<section class="search container-fluid overh">
	<form action="#" class="form-inline mt10">
		<div class="row">
			<div class="col-xs-9">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search something">
				</div>
			</div>
			<div class="col-xs-3">
				<button type="submit" class="btn btn-success">Search</button>
			</div>
		</div>
	</form>
</section>
 -->
<section class="container-fluid overh">
	
	<div class="row sort_list bgf text-center">
		<div class="col-xs-3"><a href="{{ url()->current() }}?sort=sort&sc=asc"@if($active == '1') class="active"@endif><span class="glyphicon glyphicon-th-list"></span>综合</a></div>
		<div class="col-xs-3"><a href="{{ url()->current() }}?sort=sales&sc=desc"@if($active == '2') class="active"@endif><span class="glyphicon glyphicon-share"></span>销量</a></div>
		<div class="col-xs-3"><a href="{{ url()->current() }}?sort=id"@if($active == '3') class="active"@endif><span class="glyphicon glyphicon-sort-by-attributes-alt"></span>新品</a></div>
		<div class="col-xs-3"><a href="{{ url()->current() }}?sort=price&sc=asc"@if($active == '4') class="active"@endif><span class="glyphicon glyphicon-jpy"></span>价格</a></div>
	</div>

	<div class="row good_list mt10">
		
		@foreach($list as $l)
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

	<div class="pages">
        {!! $list->appends(['sort'=>$sort,'sc'=>$sc])->links() !!}
    </div>

</section>

	
@include('home.foot')
@endsection
