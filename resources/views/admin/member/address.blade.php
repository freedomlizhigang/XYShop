@extends('admin.right')


@section('content')


<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th width="200">姓名</th>
		<th width="120">电话</th>
		<th>地址</th>
		<th width="80">操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>@if($m->default == 1) <span class="label label-primary">默认</span> @endif{{ $m->people }}</td>
		<td>{{ $m->phone }}</td>
		<td>{{ $m->area }}-{{ $m->address }}</td>
		<td>
			@if(App::make('com')->ifCan('user-addressedit'))
			<div data-url="{{ url('/console/user/addressedit',$m->id) }}" data-title="修改配送地址" title="修改配送地址" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
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