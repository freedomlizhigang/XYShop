@extends('admin.right')



@section('content')

<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th>标题</th>
		<th width="100">分类</th>
		<th width="100">价格</th>
		<th width="100">库存</th>
		<th width="180">修改时间</th>
		<th width="200">操作</th>
	</tr>
	@foreach($list as $a)
	<tr>
		<td>{{ $a->id }}</td>
		<td>
			{{ $a->title }}
		</td>
		<td>{{ cache('goodcateCache')[$a->cate_id]['name'] }}</td>
		<td><span class="text-success">{{ $a->shop_price }}￥</span></td>
		<td><span class="text-primary">{{ $a->store }}</span></td>
		<td>{{ $a->updated_at }}</td>
		<td>
			@if(App::make('com')->ifCan('good-edit'))
			<a href="{{ url('/console/good/edit',$a->id) }}" class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></a>
			@endif
			@if(App::make('com')->ifCan('promotion-rmgood'))
			<a href="{{ url('/console/promotion/rmgood',['id'=>$id,'gid'=>$a->id]) }}" class="confirm btn btn-xs btn-danger glyphicon glyphicon-trash"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>
@endsection