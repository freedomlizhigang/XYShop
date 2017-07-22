@extends('admin.right')

@if(App::make('com')->ifCan('log-del'))
@section('rmenu')
	<a href="{{ url('/console/log/del') }}" class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-trash"></span> 清除七天前日志</a>
@endsection
@endif

@section('content')
<!-- 按用户查看 -->
<div class="clearfix">
	<form action="" method="get" class="form-inline">
		<div class="form-group">
			<select name="admin_id" id="admin_id" class="form-control">
				<option value="">请选择</option>
				@foreach($admins as $a)
				<option value="{{ $a->id }}">{{ $a->name }} - {{ $a->realname }}</option>
				@endforeach
			</select>
			<button class="btn btn-xs btn-info">查找</button>
		</div>
	</form>
</div>

<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th width="120">用户</th>
		<th>url</th>
		<th width="180">插入时间</th>
	</tr>
	@foreach($list as $a)
	<tr>
		<td>{{ $a->id }}</td>
		<td>{{ $a->user }}</td>
		<td>{{ $a->url }}</td>
		<td>{{ $a->created_at }}</td>
	</tr>
	@endforeach
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->links() !!}
</div>
@endsection