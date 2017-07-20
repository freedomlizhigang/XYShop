@extends('admin.right')

@if(App::make('com')->ifCan('section-add'))
@section('rmenu')
	<div data-url="{{ url('/console/section/add') }}" data-title="添加部门" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加部门</div>
@endsection
@endif

@section('content')
<table class="table table-striped table-hover">
	<tr class="active">
		<th width="50">ID</th>
		<th>部门名称</th>
		<th>状态</th>
		<th>操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>{{ $m->name }}</td>
		<td>
			@if($m->status == 1)
			<span class="text-success">正常</span>
			@else
			<span class="color_red">禁用</span>
			@endif
		</td>
		<td>
			@if(App::make('com')->ifCan('section-edit'))
			<div data-url="{{ url('/console/section/edit',$m->id) }}" data-title="修改" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('section-del'))
			<a href="{{ url('/console/section/del',$m->id) }}" class="btn btn-xs btn-danger glyphicon glyphicon-trash confirm"></a>
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