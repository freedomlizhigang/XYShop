@extends('home.layout')


@section('title')
    <title>{{ $info->title }}</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection


@section('content')

	<div class="container-fluid bgf">

		<ul class="list_yhq clearfix">
			@foreach($list as $l)
			<li class="mt10 yhq_out">
				<div class="yhq_con row">
					<div class="col-xs-4">
						<span class="fz24">￥</span>
						<span class="fz14">{{ $l->yhq->lessprice }}</span>
					</div>
					<div class="col-xs-4 text-center">
						<span class="yhq_m">优惠券</span>
					</div>
					<div class="col-xs-4 text-right">
						<span class="yhq_r">@if($l->status &&  $l->endtime > date('Y-m-d H:i:s'))可用@else失效@endif</span>
					</div>
				</div>
				<div class="yhq_con_b">
					<p class="yhq_tt"><span class="glyphicon glyphicon-text-size"></span>{{ $l->yhq->title }}</p>
					<p class="yhq_price"><span class="glyphicon glyphicon-saved"></span>使用门槛：满 {{ $l->yhq->price }} 元可用</p>
					<p class="yhq_time"><span class="glyphicon glyphicon-bell"></span>使用时间：{{ str_limit($l->yhq->starttime,10,'') }} 至 {{ str_limit($l->yhq->endtime,10,'') }}</p>
				</div>
			</li>
			@endforeach
		</ul>

		<div class="pages">
            {!! $list->links() !!}
        </div>
	</div>
@include('home.foot')
@endsection