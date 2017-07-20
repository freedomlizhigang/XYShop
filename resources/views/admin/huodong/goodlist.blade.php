@extends('admin.right')



@section('content')

<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th>标题</th>
		<th width="100">分类</th>
		<th width="180">修改时间</th>
		<th width="200">操作</th>
	</tr>
	@foreach($list as $a)
	<tr>
		<td>{{ $a->id }}</td>
		<td>
			@if($a->isnew == 1)
			<span class="text-danger">[新品]</span>
			@endif
			@if($a->isxs == 1)
			<span class="text-primary">[限时]</span>
			@endif
			@if($a->isxl == 1)
			<span class="text-success">[限量]</span>
			@endif
			{{ $a->title }}
		</td>
		<td>{{ cache('goodcateCache')[$a->cate_id]['name'] }}</td>
		<td>{{ $a->updated_at }}</td>
		<td>
			@if(App::make('com')->ifCan('good-edit'))
			<a href="{{ url('/console/good/edit',$a->id) }}" class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></a>
			@endif
			@if(App::make('com')->ifCan('huodong-rmgood'))
			<a href="{{ url('/console/huodong/rmgood',['id'=>$id,'gid'=>$a->id]) }}" class="confirm btn btn-xs btn-danger glyphicon glyphicon-trash"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>
@endsection