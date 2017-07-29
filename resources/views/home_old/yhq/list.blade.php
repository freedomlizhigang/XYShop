@extends('home.layout')


@section('title')
    <title>{{ $info->title }}</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection


@section('content')
	<div class="container-fluid bgf">

		<ul class="list_yhq clearfix mt20">
			@foreach($list as $l)
			<li class="mt10 yhq_out">
				<div class="yhq_con row">
					<div class="col-xs-4">
						<span class="fz24">￥</span>
						<span class="fz14">{{ $l->lessprice }}</span>
					</div>
					<div class="col-xs-4 text-center">
						<span class="yhq_m">优惠券</span>
					</div>
					<div class="col-xs-4 text-right">
						<pan class="yhq_r" data-yid="{{ $l->id }}">领取</pan>
					</div>
				</div>
				<div class="yhq_con_b">
					<p class="yhq_tt"><span class="glyphicon glyphicon-text-size"></span>{{ $l->title }}</p>
					<p class="yhq_price"><span class="glyphicon glyphicon-saved"></span>使用门槛：满 {{ $l->price }} 元可用</p>
					<p class="yhq_time"><span class="glyphicon glyphicon-bell"></span>使用时间：{{ str_limit($l->starttime,10,'') }} 至 {{ str_limit($l->endtime,10,'') }}</p>
					<p><span class="glyphicon glyphicon-tasks"></span>剩余：{{ $l->nums }}张</p>
				</div>
			</li>
			@endforeach
		</ul>

	</div>
<script>
	$(function(){
		var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
		$('.yhq_r').click(function() {
			if(before_request == 0)return false;
			var that = $(this);
			var yid = that.attr('data-yid');
			var uid = "{{ session('member')->id }}";
			// 查一下是否比总价多，不多，不可用
			$.post( host +'api/common/yhq/get',{yid:yid,uid:uid},function(d){
				var ss = jQuery.parseJSON(d);
				alert(ss.msg);
				before_request = 1;
				return;
			}).error(function() {
				before_request = 1;
				return;
			});
		});
	})
</script>

@include('home.foot')
@endsection