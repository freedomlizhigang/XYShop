@extends('admin.right')


@section('content')
<style>
	.todays .label {
		font-weight: normal;
		display: inline-block;
	}
	.nums {font-size: 16px;font-weight: bold;}
</style>

<div class="todays">
	<span class="label label-info">已收款数：<span class="nums">{{ $today_inc }}</span> 元</span>
	<span class="label label-warning">充值数：<span class="nums">{{ $today_dec }}</span> 元</span>
	<span class="label label-danger">结余：<span class="nums">{{ $today_over }}</span> 元</span>
</div>

<div class="todays mt10">
	<form action="" class="form-inline form_excel" method="get">
		开始时间：<input type="text" name="starttime" class="form-control mr10" value="{{ $starttime }}" id="laydate">
		到：<input type="text" name="endtime" class="form-control" value="{{ $endtime }}" id="laydate2">
		<button class="btn btn-xs btn-success btn_search">查询</button>
		@if(App::make('com')->ifCan('index-excel_consume'))
		<button class="btn btn-xs btn-primary btn_order">导出表格</button>
		@endif
	</form>
</div>

<!-- 今日销售统计表 -->
<div class="good_ship mt10">
	<table class="table table-striped">
		<tr class="active">
			<th width="120">用户</th>
			<th width="200">备注</th>
			<th width="110">金额</th>
			<th>时间</th>
		</tr>
		@foreach($consume as $g)
		<tr>
			<td>@if(!is_null($g->user)){{ $g->user->nickname }}@endif</td>
			<td>{{ $g->mark }}</td>
			<td><span class="text-danger">@if($g->type == 1)+@else-@endif</span> {{ $g->price }}</td>
			<td>{{ $g->created_at }}</td>
		</tr>
		@endforeach
	</table>
</div>

<script>
	$(function(){
        $('.btn_search').click(function(){
            $('.form_excel').attr('action',"").submit();
        });
		$('.btn_order').click(function(){
			$('.form_excel').attr('action',"{{ url('/console/index/excel_consume') }}").submit();
		});
	})
	laydate({
        elem: '#laydate',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime:true,
        istoday: true, //是否显示今天
    });
    laydate({
        elem: '#laydate2',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
        istoday: true, //是否显示今天
    });
</script>

@endsection