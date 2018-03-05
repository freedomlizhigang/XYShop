@extends('admin.right')

@section('content')
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th width="150">名称</th>
		<th>规格项</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>{{ $m->name }}</td>
		<td>
			@foreach($m->goodspecitem as $k => $v)
			@if($k != 0)，@endif{{ $v->item }}
			@endforeach
		</td>
	</tr>
	@endforeach
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->links() !!}
</div>
@endsection