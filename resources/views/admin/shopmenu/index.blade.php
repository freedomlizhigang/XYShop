@extends('admin.right')

@if(App::make('com')->ifCan('shopmenu-add'))
@section('rmenu')
	<div data-url="{{ url('/console/shopmenu/add') }}" data-title="添加菜单" data-toggle='modal' data-target='#myModal' class="btn btn-default btn-xs btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加菜单</div>
@endsection
@endif

@section('content')

<table class="table table-striped table-hover">
	<thead>
		<tr class="active">
			<td width="60">排序</td>
			<td width="60">ID</td>
			<td>菜单名称</td>
			<td>url</td>
			<td>显示</td>
			<td>操作</td>
		</tr>
	</thead>
	<tbody>
	{!! $treeHtml !!}
	</tbody>
</table>
@endsection