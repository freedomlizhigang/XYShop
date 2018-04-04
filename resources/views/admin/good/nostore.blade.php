@extends('admin.right')



@section('content')

<form action="" class="form-inline form_submit" method="get">
{{ csrf_field() }}
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th>标题</th>
		<th width="100">分类</th>
		<th width="100">价格</th>
		<th width="100">库存</th>
		<th width="80">状态</th>
		<th width="180">修改时间</th>
		<th width="280">操作</th>
	</tr>
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
		<td>￥{{ $a->price }}</td>
		<td>{{ $a->store }}</td>
		<td>
			@if($a->status == 1)
			<span class="text-success">在售</span>
			@elseif($a->status == 0)
			<span class="text-danger">下架</span>
			@endif
		</td>
		<td>{{ $a->updated_at }}</td>
		<td>
			@if(App::make('com')->ifCan('good-edit'))
			<a href="{{ url('/console/good/edit',$a->id) }}" class="btn btn-xs btn-info glyphicon glyphicon-edit"></a>
			@endif
			<a href="{{ url('/good',['id'=>$a->id]) }}" target="_blank" class="btn btn-xs btn-success glyphicon glyphicon-eye-open"></a>
			@if(App::make('com')->ifCan('good-del') && $a->status == 0)
			<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>1]) }}" title="上架" class="btn btn-xs btn-success glyphicon glyphicon-ok-circle"></a>
			@endif
			@if(App::make('com')->ifCan('good-del') && $a->status == 1)
			<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>0]) }}" title="下架" class="btn btn-xs btn-warning glyphicon glyphicon-ban-circle"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>

</form>
<!-- 分页，appends是给分页添加参数 -->
<div class="pull-right">
	<div class="pull-left mr10 mt5">总共 {{ $count }} 条</div>
	{!! $list->links() !!}
</div>
@endsection