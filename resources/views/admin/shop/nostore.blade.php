@extends('admin.right')



@section('content')

<form action="" class="form-inline form_submit" method="get">
{{ csrf_field() }}
<table class="table table-striped table-hover mt10">
	<thead>
		<tr class="success">
			<th width="50">ID</th>
			<th>标题</th>
			<th width="100">分类</th>
			<th width="100">价格</th>
			<th width="100">库存</th>
			<th width="80">状态</th>
			<th width="180">修改时间</th>
			<th width="280">操作</th>
		</tr>
	</thead>
	<tbody>
	@foreach($list as $a)
		<tr>
			<td>{{ $a->id }}</td>
			<td>
				<img src="{{ $a->thumb }}" width="100" height="auto" class="img-responsive pull-left img-rounded mr10" alt="">
				@if($a->tags == '')
				<span class="text-danger">{{ $a->tags }}</span>
				@endif
				@if($a->isxs == 1)
				<span class="text-primary">[限时]</span>
				@endif
				@if($a->isxl == 1)
				<span class="text-success">[限量]</span>
				@endif
				{{ $a->title }}
				@foreach($a->goodspecprice as $gp)
				<br /><span class="label label-info">{{ $gp->key_name }}</span>
				@endforeach
			</td>
			<td>@if(isset(cache('goodcateCache')[$a->cate_id])){{ cache('goodcateCache')[$a->cate_id]['name'] }}@endif</td>
			<td>
			@if($a->goodspecprice->count() == 0)
			￥{{ $a->price }}
			@else
			@foreach($a->goodspecprice as $gp)
			<p>￥{{ $gp->price }}</p>
			@endforeach
			@endif
			</td>
			<td>
			@if($a->goodspecprice->count() == 0)
			{{ $a->store }}
			@else
			@foreach($a->goodspecprice as $gp)
			<p>{{ $gp->store }}</p>
			@endforeach
			@endif
			</td>
			<td>
				@if($a->status == 1)
				<span class="text-success">在售</span>
				@elseif($a->status == 0)
				<span class="color-warning">下架</span>
				@endif
			</td>
			<td>{{ $a->updated_at }}</td>
			<td>
				@if(App::make('com')->ifCan('good-edit'))
				<a href="{{ url('/console/good/edit',$a->id) }}" class="btn btn-sm btn-info">修改</a>
				@endif
				<a href="{{ url('/shop/good',['id'=>$a->id]) }}" target="_blank" class="btn btn-sm btn-warning">查看</a>
				@if(App::make('com')->ifCan('good-del') && $a->status == 0)
				<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>1]) }}" class="confirm btn btn-sm btn-danger">上架</a>
				@endif
				@if(App::make('com')->ifCan('good-del') && $a->status == 1)
				<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>0]) }}" class="confirm btn btn-sm btn-danger">下架</a>
				@endif
			</td>
		</tr>
	@endforeach
	@foreach($list_to as $a)
		<tr>
			<td>{{ $a->id }}</td>
			<td>
				<img src="{{ $a->thumb }}" width="100" height="auto" class="img-responsive pull-left img-rounded mr10" alt="">
				@if($a->tags == '')
				<span class="text-danger">{{ $a->tags }}</span>
				@endif
				@if($a->isxs == 1)
				<span class="text-primary">[限时]</span>
				@endif
				@if($a->isxl == 1)
				<span class="text-success">[限量]</span>
				@endif
				{{ $a->title }}
				@foreach($a->goodspecprice as $gp)
				<br /><span class="label label-info">{{ $gp->key_name }}</span>
				@endforeach
			</td>
			<td>@if(isset(cache('goodcateCache')[$a->cate_id])){{ cache('goodcateCache')[$a->cate_id]['name'] }}@endif</td>
			<td>
			@if($a->goodspecprice->count() == 0)
			￥{{ $a->price }}
			@else
			@foreach($a->goodspecprice as $gp)
			<p>￥{{ $gp->price }}</p>
			@endforeach
			@endif
			</td>
			<td>
			@if($a->goodspecprice->count() == 0)
			{{ $a->store }}
			@else
			@foreach($a->goodspecprice as $gp)
			<p>{{ $gp->store }}</p>
			@endforeach
			@endif
			</td>
			<td>
				@if($a->status == 1)
				<span class="text-success">在售</span>
				@elseif($a->status == 0)
				<span class="color-warning">下架</span>
				@endif
			</td>
			<td>{{ $a->updated_at }}</td>
			<td>
				@if(App::make('com')->ifCan('good-edit'))
				<a href="{{ url('/console/good/edit',$a->id) }}" class="btn btn-sm btn-info">修改</a>
				@endif
				<a href="{{ url('/shop/good',['id'=>$a->id]) }}" target="_blank" class="btn btn-sm btn-warning">查看</a>
				@if(App::make('com')->ifCan('good-del') && $a->status == 0)
				<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>1]) }}" class="confirm btn btn-sm btn-danger">上架</a>
				@endif
				@if(App::make('com')->ifCan('good-del') && $a->status == 1)
				<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>0]) }}" class="confirm btn btn-sm btn-danger">下架</a>
				@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

</form>
<!-- 分页，appends是给分页添加参数 -->
<div class="label label-danger">总共 {{ $count }} 条</div>
@endsection