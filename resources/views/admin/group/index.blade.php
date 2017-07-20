@extends('admin.right')

@if(App::make('com')->ifCan('group-add'))
@section('rmenu')
	<div data-url="{{ url('/console/group/add') }}" data-title="添加用户组" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加用户组</div>
@endsection
@endif

@section('content')
<table class="table table-striped table-hover">
	<tr class="active">
		<th width="50">ID</th>
		<th width="200">用户组</th>
		<th width="200">所需积分</th>
		<th>折扣</th>
		<th>操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>{{ $m->name }}</td>
		<td>{{ $m->points }}</td>
		<td class="text-success">{{ $m->discount }}%</td>
		<td>
			@if(App::make('com')->ifCan('group-edit'))
			<div data-url="{{ url('/console/group/edit',$m->id) }}" data-title="修改" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('group-del'))
			<a href="{{ url('/console/group/del',$m->id) }}" class="btn btn-xs btn-danger glyphicon glyphicon-trash confirm"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->links() !!}
</div>
@endsection