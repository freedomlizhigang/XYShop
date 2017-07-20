@extends('admin.right')


@section('rmenu')
	@if(App::make('com')->ifCan('type-add'))
	<div data-url="{{ url('/console/type/add',['pid'=>$pid]) }}" data-title="添加分类" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加分类</div>
	@endif
@endsection


@section('content')

<table class="table table-striped table-hover">
	<thead>
		<tr class="active">
			<td width="60">排序</td>
			<td width="60">ID</td>
			<td>分类名称</td>
			<td>操作</td>
		</tr>
	</thead>
	<tbody>
	@foreach($list as $l)
		<tr>
			<td>{{ $l->sort }}</td>
			<td>{{ $l->id }}</td>
			<td><a href="{{ url('/console/type/index',['id'=>$l->id]) }}">{{ $l->name }}</a>
			</td>
			<td>
				@if(App::make('com')->ifCan('type-add'))
				<div data-url="{{ url('/console/type/add',['id'=>$l->id]) }}" data-title="添加分类" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-primary glyphicon glyphicon-plus btn_modal"></div>
				@endif
				@if(App::make('com')->ifCan('type-edit'))
				<div data-url="{{ url('/console/type/edit',['id'=>$l->id]) }}" data-title="修改分类" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div> 
				@endif
				@if(App::make('com')->ifCan('type-del'))
				<a href="{{ url('/console/type/del',['id'=>$l->id]) }}" class="btn btn-xs btn-danger glyphicon glyphicon-trash confirm"></a>
				@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@endsection