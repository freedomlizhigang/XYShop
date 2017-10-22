@extends('admin.right')


@section('content')
<!-- 选出栏目 -->
<div class="clearfix">
	<form action="" class="form-inline pull-left form_excel" method="get">
		<select name="status" id="status" class="form-control mr10">
			<option value="">请选择状态</option>
			<option value="1"@if($status == '1') selected="selected" @endif>进行中</option>
			<option value="0"@if($status == '0') selected="selected" @endif>关闭</option>
		</select>
		开始时间：<input type="text" name="starttime" class="form-control mr10" value="{{ $starttime }}" id="laydate">
		到：<input type="text" name="endtime" class="form-control" value="{{ $endtime }}" id="laydate2">
		<input type="text" name="key" value="{{ $key }}" class="form-control" placeholder="请输入商品名查询..">
		<button class="btn btn-xs btn-info btn_find">查找</button>
		@if(App::make('com')->ifCan('returngood-excel'))
		<button class="btn btn-xs btn-primary btn_order">导出</button>
		@endif
	</form>

	<form action="" class="form-inline pull-right" method="get">
		<input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="请输入手机号或昵称查询..">
		<button class="btn btn-xs btn-info">搜索</button>
	</form>
</div>
<form action="" class="form-inline form_submit" method="get">
{{ csrf_field() }}
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th width="150">用户</th>
		<th width="150">订单号</th>
		<th>商品名</th>
		<th width="150">备注</th>
		<th width="150">处理意见</th>
		<th width="50">数量</th>
		<th width="50">总价</th>
		<th width="80">状态</th>
		<th width="160">提交时间</th>
		<th width="160">退货时间</th>
		<th width="80">操作</th>
	</tr>
	@foreach($list as $a)
	<tr>
		<td>{{ $a->id }}</td>
		<td>@if(!is_null($a->user)){{ $a->user->username }} - {{ $a->user->nickname }}@endif</td>
		<td>{{ $a->order->order_id }}</td>
		<td>
			{{ $a->good_title }} - {{ $a->good_spec_name }}
		</td>
		<td>{{ $a->mark }}</td>
		<td>{{ $a->shopmark }}</td>
		<td>{{ $a->nums }}</td>
		<td>￥{{ $a->total_prices }}</td>
		<td>
			@if($a->status == 0)
			<span class="text-primary">未处理</span>
			@elseif($a->status == 1)
			<span class="text-success">已退货</span>
			@else
			<span class="text-warning">不退货</span>
			@endif
		</td>
		<td>{{ $a->created_at }}</td>
		<td>{{ $a->return_time }}</td>
		<td>
			@if(App::make('com')->ifCan('returngood-status') && $a->status == 0)
			<div data-url="{{ url('/console/returngood/status',['id'=>$a->id]) }}" data-title="处理退货" title="处理退货" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
		</td>
	</tr>
	@endforeach
</table>
</form>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->appends(['status'=>$status,'starttime'=>$starttime,'endtime'=>$endtime])->links() !!}
</div>

<!-- 选中当前栏目 -->
<script>
	$(function(){
		$('.btn_find').click(function(){
			$('.form_excel').attr('action',"").submit();
		});
		$('.btn_order').click(function(){
			$('.form_excel').attr('action',"{{ url('/console/returngood/excel') }}").submit();
		});
	});
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