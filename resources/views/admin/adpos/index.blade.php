@extends('admin.right')


@section('rmenu')
	@if(App::make('com')->ifCan('adpos-add'))
	<div data-url="{{ url('/console/adpos/add') }}" data-title="添加广告位" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加广告位</div>
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
		<td>名称</td>
		<td width="200">PC/MOBILE</td>
		<td width="100">操作</td>
	</tr>
	@foreach($list as $l)
	<tr>
		<td>{{ $l->id }}</td>
		<td>
		{{ $l->name }}
		</td>
		<td>
			@if($l->is_mobile == 1)
			<span class="text-primary">手机</span>
			@else
			<span class="text-success">电脑</span>
			@endif
		</td>
		<td>
			@if(App::make('com')->ifCan('adpos-edit'))
			<div data-url="{{ url('/console/adpos/edit',['id'=>$l->id]) }}" data-title="修改广告位" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('adpos-del'))
			<a href="{{ url('/console/adpos/del',['id'=>$l->id]) }}" class="btn btn-xs btn-danger glyphicon glyphicon-trash confirm"></a>
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