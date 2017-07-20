@extends('admin.right')


@section('content')

<div class="clearfix">
	<form action="" class="form-inline pull-left" method="get">
		开始时间：<input type="text" name="starttime" class="form-control" id="laydate">
		结束时间：<input type="text" name="endtime" class="form-control" id="laydate2">
		<button class="btn btn-sm btn-info">查找</button>
	</form>

	<form action="" class="form-inline pull-right" method="get">
		<input type="text" name="q" class="form-control" placeholder="请输入名称..">
		<button class="btn btn-sm btn-info">搜索</button>
	</form>
</div>

<table class="table table-striped table-hover mt10">
	<thead>
		<tr class="success">
			<th width="50">ID</th>
			<th width="150">文件名</th>
			<th>url</th>
			<th>插入时间</th>
			<th>管理</th>
		</tr>
	</thead>
	<tbody>
	@foreach($list as $a)
		<tr>
			<td>{{ $a->id }}</td>
			<td><a href="{{ $a->url }}" target="_blank">{{ $a->filename }}</a></td>
			<td>{{ $a->url }}</td>
			<td>{{ $a->created_at }}</td>
			<td><a href="{{ url('admin/attr/delfile',$a->id) }}" class="confirm btn btn-sm btn-danger">删除</a></td>
		</tr>
	@endforeach
	</tbody>
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->appends(['starttime' =>$starttime,'endtime'=>$endtime,'q'=>$key])->links() !!}
</div>
<script>
	laydate({
        elem: '#laydate',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        festival: true,
        istoday: true,
        max: laydate.now(), //最大日期
        start: laydate.now('0, "YYYY-MM-DD hh:mm:ss"'),
        istime: true,
    });
    laydate({
        elem: '#laydate2',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        festival: true,
        istoday: true,
        max: laydate.now(), //最大日期
        start: laydate.now('0, "YYYY-MM-DD hh:mm:ss"'),
        istime: true,
    });
</script>
@endsection