@extends('admin.right')


@section('rmenu')
	@if(App::make('com')->ifCan('brand-add'))
	<div data-url="{{ url('/console/brand/add') }}" data-title="添加品牌" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加品牌</div>
	@endif
@endsection


@section('content')

<div class="clearfix">
	<form action="" class="form-inline" method="get">
		<input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="请输入关键字..">
		<button class="btn btn-xs btn-info">搜索</button>
	</form>
</div>
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<td width="60">ID</td>
		<td width="120">名称</td>
		<td width="200">logo</td>
		<td>分类</td>
		<td width="100">操作</td>
	</tr>
	@foreach($list as $l)
	<tr>
		<td>{{ $l->id }}</td>
		<td>
		{{ $l->name }}
		</td>
		<td>
			<img src="{{ $l->icon }}" class="img-responsive img-rounded pull-left mr10" alt="">
		</td>
		<td>
			{{ isset(cache('goodcateCache')[$l->goodcate_parentid]) ? cache('goodcateCache')[$l->goodcate_parentid]['name'] .' -> '. cache('goodcateCache')[$l->goodcate_id]['name'] : '' }}
		</td>
		<td>
			@if(App::make('com')->ifCan('brand-edit'))
			<div data-url="{{ url('/console/brand/edit',['id'=>$l->id]) }}" data-title="修改品牌" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('brand-del'))
			<a href="{{ url('/console/brand/del',['id'=>$l->id]) }}" class="confirm btn btn-xs btn-danger glyphicon glyphicon-trash"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->appends(['q'=>$q])->links() !!}
</div>
@endsection