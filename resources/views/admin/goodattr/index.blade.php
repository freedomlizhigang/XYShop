@extends('admin.right')

@if(App::make('com')->ifCan('goodattr-add'))
@section('rmenu')
	<div data-url="{{ url('/console/goodattr/add') }}" data-title="添加商品属性" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加商品属性</div>
@endsection
@endif

@section('content')
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th width="150">商品分类</th>
		<th width="150">属性名</th>
		<th>值</th>
		<th>操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>@if(!is_null($m->goodcate)){{ $m->goodcate->name }}@endif</td>
		<td>{{ $m->name }}</td>
		<td>{{ $m->value }}</td>
		<td>
			@if(App::make('com')->ifCan('goodattr-edit'))
			<div data-url="{{ url('/console/goodattr/edit',['id'=>$m->id]) }}" data-title="修改属性" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('goodattr-del'))
			<a href="{{ url('/console/goodattr/del',$m->id) }}" class="confirm btn btn-xs btn-danger glyphicon glyphicon-trash"></a>
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