@extends('admin.right')


@section('content')


<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th>备注</th>
		<th width="220">金额变动情况</th>
		<th>消费时间</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>{{ $m->mark }}</td>
		<td><span class="text-danger">@if($m->type == 1)+@else-@endif</span>{{ $m->price }}</td>
		<td>{{ $m->created_at }}</td>
	</tr>
	@endforeach
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->links() !!}
</div>

@endsection