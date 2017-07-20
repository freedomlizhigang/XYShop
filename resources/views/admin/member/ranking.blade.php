@extends('admin.right')


@section('content')

<div class="clearfix">
	<form action="" class="form-inline form_excel pull-left" method="get">
		<input name="starttime" placeholder="开始时间" class="form-control" id="laydate">
		<input name="endtime" placeholder="结束时间" class="form-control" id="laydate2">
		<button class="btn btn-xs btn-info">查找</button>
	</form>

</div>

<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="300">用户</th>
		<th>总数</th>
	</tr>
	@foreach($user as $m)
	<tr>
		<td>@if(!is_null($m['user'])){{ $m['user']['nickname'] }} - {{ $m['user']['phone'] }}@endif</td>
		<td>{{ $m['total'] }}</td>
	</tr>
	@endforeach
</table>

<script>
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