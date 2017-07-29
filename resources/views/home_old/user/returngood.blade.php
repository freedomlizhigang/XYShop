@extends('home.layout')

@section('title')
    <title>退货管理-{{ cache('config')['sitename'] }}</title>
@endsection

@section('content')
<div class="bgf">
	<div class="good_cart_list overh">
		@foreach($list as $l)
		<div class="mt5 good_cart_list_div">
			<div class="media">
				<div class="pull-left"><img src="{{ $l->good->thumb }}" width="100" class="media-object img-thumbnail" alt=""></div>
				<div class="media-body">
					<h4>
					@if($l->status == 0)
					<span class="label label-warning">未处理</span>
					@elseif($l->status == 1)
					<span class="label label-success">已退货</span>
					@else
					<span class="label label-danger">未退</span>
					@endif
					{{ $l->shopmark }}</h4>
					<h5 class="mt5">{{ $l->good->title }}</h5>
					<!-- 价格 -->
					<p class="fs12">价格：<span class="good_prices text-success">￥{{ $l->price }}</span></p>
					<p class="fs12">金额：<span class="good_prices text-danger">￥{{ $l->total_prices }}</span></p>
				</div>
			</div>
		</div>
		@endforeach
	</div>
	<div class="pages">
        {!! $list->links() !!}
    </div>
</div>
@include('home.foot')
@endsection