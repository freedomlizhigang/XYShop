@extends('admin.right')


@section('rmenu')
	@if(App::make('com')->ifCan('area-add'))
	<div data-url="{{ url('/console/area/add',['pid'=>$pid]) }}" data-title="添加地区" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加地区</div>
	@endif
@endsection


@section('content')

<table class="table table-striped table-hover">
	<thead>
		<tr class="active">
			<td width="60">排序</td>
			<td width="60">ID</td>
			<td>地区名称</td>
			<td>是否显示</td>
			<td>操作</td>
		</tr>
	</thead>
	<tbody>
	@foreach($list as $l)
		<tr>
			<td>{{ $l->sort }}</td>
			<td>{{ $l->id }}</td>
			<td><a href="{{ url('/console/area/index',['id'=>$l->id]) }}">{{ $l->areaname }}</a>
			</td>
			<td>
				@if($l->is_show == 1)
				<span class="text-success">显示</span>
				@else
				<span class="text-warning">隐藏</span>
				@endif
			</td>
			<td>
				@if(App::make('com')->ifCan('area-add'))
				<div data-url="{{ url('/console/area/add',['pid'=>$l->id]) }}" data-title="添加地区" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-primary glyphicon glyphicon-plus btn_modal"></div>
				@endif
				@if(App::make('com')->ifCan('area-edit'))
				<div data-url="{{ url('/console/area/edit',['id'=>$l->id]) }}" data-title="修改地区" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
				@endif
				@if(App::make('com')->ifCan('area-del'))
				<a href="{{ url('/console/area/del',['id'=>$l->id]) }}" class="btn btn-xs btn-danger glyphicon glyphicon-trash confirm"></a>
				@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@endsection