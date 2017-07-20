@extends('admin.right')


@section('content')
<table class="table table-striped table-hover">
	<tr class="active">
		<th width="50">ID</th>
		<th width="120">支付方式</th>
		<th width="120">Code</th>
		<th>介绍</th>
		<th width="120">状态</th>
		<th width="150">操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>{{ $m->name }}</td>
		<td>{{ $m->code }}</td>
		<td>{{ $m->content }}</td>
		<td>
			@if($m->paystatus == 1)
			<span class="text-success">开启</span>
			@else
			<span class="color_red">关闭</span>
			@endif
		</td>
		<td>
			@if(App::make('com')->ifCan('pay-edit'))
			<a href="{{ url('/console/pay/edit',$m->id) }}" class="btn btn-xs btn-info glyphicon glyphicon-edit"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>
@endsection