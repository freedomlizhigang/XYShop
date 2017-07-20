@extends('admin.right')


@if(App::make('com')->ifCan('index-consume'))
@section('rmenu')
	<a href="{{ url('/console/index/consume') }}" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-eye-open"></span> 消费情况</a>
@endsection
@endif


@section('content')


<style>
	.todays .label {
		font-weight: normal;
		display: inline-block;
	}
	.nums {font-size: 16px;font-weight: bold;}
	.good_ship {margin-top: 20px;}
</style>

<div class="todays">
	<span class="label label-primary">总订单量：<span class="nums">{{ $data['today_ordernum'] }}</span>个</span>
	<span class="label label-success">销售额：<span class="nums">{{ $data['today_prices'] }}</span> 元</span>
	<span class="label label-info">已收款数：<span class="nums">{{ $data['today_prices_real'] }}</span> 元</span>
	<span class="label label-warning">未收款数：<span class="nums">{{ $data['today_prices_no'] }}</span> 元</span>
	<span class="label label-danger">待发货：<span class="nums">{{ $data['today_ship'] }}</span> 件</span>
</div>

<div class="todays mt10">
	<form action="" class="form-inline form_excel" method="get">
		开始时间：<input type="text" name="starttime" class="form-control mr10" value="" id="laydate">
		到：<input type="text" name="endtime" class="form-control" value="" id="laydate2">
		<button class="btn btn-xs btn-info">查询</button>
		@if(App::make('com')->ifCan('index-excel_goods'))
		<button class="btn btn-xs btn-success btn_goods">导出销售统计表</button>
		@endif
		@if(App::make('com')->ifCan('index-excel_store'))
		<button class="btn btn-xs btn-warning btn_store">导出库房表</button>
		@endif
	</form>
</div>

<!-- 销售统计表 -->
<div class="good_ship">
	<table class="table table-striped">
		<tr class="active">
			<th>标题</th>
			<th>货号</th>
			<th>数量</th>
			<th>单件重量</th>
			<th>总重量</th>
			<th>总价</th>
		</tr>
		@foreach($good_ship as $g)
		<tr>
			<td>{{ $g['title'] }}@if($g['good_spec_name'] != '') <span class="label label-default">{{ $g['good_spec_name'] }}</span>@endif</td>
			<td>{{ $g['pronums'] }}</td>
			<td>{{ $g['nums'] }}</td>
			<td>{{ $g['weight'] }} 斤</td>
			<td><strong class="text-primary">{{ $g['total_weight'] }} 斤</strong></td>
			<td>￥{{ $g['total_prices'] }}</td>
		</tr>
		@endforeach
	</table>
</div>

<script>
	$(function(){
		$('.btn_goods').click(function(){
			$('.form_excel').attr('action',"{{ url('/console/index/excel_goods') }}").submit();
		});
		$('.btn_store').click(function(){
			$('.form_excel').attr('action',"{{ url('/console/index/excel_store') }}").submit();
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