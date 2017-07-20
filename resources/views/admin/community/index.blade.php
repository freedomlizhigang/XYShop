@extends('admin.right')


@section('rmenu')
	@if(App::make('com')->ifCan('community-add'))
	<div data-url="{{ url('/console/community/add') }}" data-title="添加社区" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加社区</div>
	@endif
@endsection


@section('content')

<div class="clearfix">
	<form action="" class="form-inline" method="get">
		<input type="text" name="q" value="{{ $q }}" class="form-control input-sm" placeholder="请输入关键字..">
		<button class="btn btn-xs btn-info">搜索</button>
	</form>
</div>
<table class="table table-striped table-hover mt10">
	<thead>
		<tr class="active">
			<td width="60">排序</td>
			<td width="60">ID</td>
			<td>社区名称</td>
			<td>是否显示</td>
			<td>操作</td>
		</tr>
	</thead>
	<tbody>
	@foreach($list as $l)
		<tr>
			<td>{{ $l->sort }}</td>
			<td>{{ $l->id }}</td>
			<td>
			@if(isset(cache('area')[$l->areaid1])){{ cache('area')[$l->areaid1]['areaname'] }} > @endif
			@if(isset(cache('area')[$l->areaid2])){{ cache('area')[$l->areaid2]['areaname'] }} > @endif
			@if(isset(cache('area')[$l->areaid3])){{ cache('area')[$l->areaid3]['areaname'] }} > @endif
			{{ $l->name }}
			</td>
			<td>
				@if($l->is_show == 1)
				<span class="text-success">显示</span>
				@else
				<span class="text-warning">隐藏</span>
				@endif
			</td>
			<td>
				@if(App::make('com')->ifCan('community-edit'))
				<div data-url="{{ url('/console/community/edit',['id'=>$l->id]) }}" data-title="修改社区" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
				@endif
				@if(App::make('com')->ifCan('community-del'))
				<a href="{{ url('/console/community/del',['id'=>$l->id]) }}" class="btn btn-xs btn-danger glyphicon glyphicon-trash confirm"></a>
				@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->appends(['q'=>$q])->links() !!}
</div>
@endsection