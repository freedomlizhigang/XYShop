@extends('home.layout')

@section('title')
    <title>会员中心-{{ cache('config')['sitename'] }}</title>
@endsection

@section('content')
<div class="bgf user_center">
	<!-- 用户基本信息 -->
	<div class="row">
		<div class="col-xs-3">
			@if($info->thumb != '') <img src="{{ $info->thumb }}" alt="" class="img-circle img-responsive">
			@else
			<img src="{{ $sites['static']}}home/images/jxf_logo.png" alt="" class="img-circle img-responsive">
			@endif
		</div>
		<div class="col-xs-9">
			<h4 class="mt20">@if($info->nickname != '') {{ $info->nickname }} @else {{ $info->username }} @endif - {{ $info->groupname }}</h4>
			<p> {{ $info->phone }}</p>
		</div>
	</div>
	<!-- 用户信息 -->
	<div class="row text-center userinfo_info">
		<div class="col-xs-4">
			<h4>{{ $info->user_money }} 元</h4>
			<p>余额</p>
		</div>
		<div class="col-xs-4 border-lr">
			<h4>{{ $info->points }} 分</h4>
			<p>积分</p>
		</div>
		<div class="col-xs-4">
			<a href="{{ url('user/yhq') }}">
				<h4>{{ $yhq_nums }} 张</h4>
				<p>优惠券</p>
			</a>
		</div>
	</div>
	<!-- 订单 -->
	<div class="user_order">
		<h4 class="user_h4">全部订单</h4>
		<div class="user_order_list clearfix mt10 text-center">
			<div class="user_order_list_div pr">
				<a href="{{ url('/user/order',['status'=>1]) }}">
					<span class="glyphicon glyphicon-usd"></span>
					<p>待付款</p>
				</a>
				@if($order_1 != 0)<div class="user_order_nums ps">{{ $order_1 }}</div>@endif
			</div>
			<div class="user_order_list_div pr">
				<a href="{{ url('/user/order',['status'=>2]) }}">
					<span class="glyphicon glyphicon-road"></span>
					<p>待发货</p>
				</a>
				@if($order_2 != 0)<div class="user_order_nums ps">{{ $order_2 }}</div>@endif
			</div>
			<div class="user_order_list_div pr">
				<a href="{{ url('/user/order',['status'=>3]) }}">
					<span class="glyphicon glyphicon-log-in"></span>
					<p>待收货</p>
				</a>
				@if($order_3 != 0)<div class="user_order_nums ps">{{ $order_3 }}</div>@endif
			</div>
			<div class="user_order_list_div pr">
				<a href="{{ url('/user/order',['status'=>4]) }}">
					<span class="glyphicon glyphicon-gift"></span>
					<p>已完成</p>
				</a>
				@if($order_4 != 0)<div class="user_order_nums ps">{{ $order_4 }}</div>@endif
			</div>
			<div class="user_order_list_div pr">
				<a href="{{ url('/user/returngood') }}">
					<span class="glyphicon glyphicon-log-out"></span>
					<p>退货</p>
				</a>
				@if($order_5 != 0)<div class="user_order_nums ps">{{ $order_5 }}</div>@endif
			</div>
		</div>
	</div>
	<!-- other -->
	<ul class="user_other">
		<li class="clearfix">
			<a href="{{ url('user/address') }}"><span class="glyphicon glyphicon-th-list"></span>我的收货地址<span class="user_other_r"> > </span></a>
		</li>
		<li class="clearfix">
			<a href="{{ url('user/info') }}"><span class="glyphicon glyphicon-cog"></span>通用设置<span class="user_other_r"> > </span></a>
		</li>
		<li class="clearfix">
			<a href="{{ url('user/card') }}"><span class="glyphicon glyphicon-credit-card"></span>充值卡激活<span class="user_other_r"> > </span></a>
		</li>
		<li class="clearfix">
			<a href="{{ url('user/consume') }}"><span class="glyphicon glyphicon-tree-deciduous"></span>消费记录<span class="user_other_r"> > </span></a>
		</li>
		<li class="clearfix">
			<a href="{{ url('oauth/wx') }}"><span class="glyphicon glyphicon-retweet"></span>绑定微信号<span class="user_other_r"> > </span></a>
		</li>
	</ul>
</div>


@include('home.foot')
@endsection