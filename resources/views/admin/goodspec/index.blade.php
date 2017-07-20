@extends('admin.right')

@if(App::make('com')->ifCan('goodspec-add'))
@section('rmenu')
	<div data-url="{{ url('/console/goodspec/add') }}" data-title="添加商品规格" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加商品规格</div>
@endsection
@endif

@section('content')
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th width="150">商品分类</th>
		<th width="150">名称</th>
		<th>规格项</th>
		<th>操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>@if(!is_null($m->goodcate)){{ $m->goodcate->name }}@endif</td>
		<td>{{ $m->name }}</td>
		<td>
			@foreach($m->goodspecitem as $k => $v)
			@if($k != 0)，@endif{{ $v->item }}
			@endforeach
		</td>
		<td>
			@if(App::make('com')->ifCan('goodspec-edit'))
			<div data-url="{{ url('/console/goodspec/edit',['id'=>$m->id]) }}" data-title="修改规格" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('goodspec-del'))
			<a href="{{ url('/console/goodspec/del',$m->id) }}" class="confirm btn btn-xs btn-danger glyphicon glyphicon-trash"></a>
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