@extends('admin.right')

@if(App::make('com')->ifCan('admin-add'))
@section('rmenu')
	<div data-url="{{ url('/console/admin/add') }}" data-title="添加用户" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加用户</div>
@endsection
@endif

@section('content')

<!-- 选出栏目 -->
<div class="">
	<form action="" class="form-inline" method="get">
		<div class="form-group">
		<input type="text" name="q" class="form-control input-sm" placeholder="请输入用户名或姓名..">
		<button class="btn btn-xs btn-info">搜索</button>
		</div>
	</form>
</div>

<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th>用户名</th>
		<th>部门</th>
		<th>角色</th>
		<th>真实姓名</th>
		<th>Email</th>
		<th>电话</th>
		<th>最后登录</th>
		<th>状态</th>
		<th>操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>{{ $m->name }}</td>
		<td>@if(!is_null($m->section)){{ $m->section->name }}@endif</td>
		<td>
			@if($m->role->count() > 0 )
			@foreach($m->role as $r)
			{{ $r->name }} | 
			@endforeach
			@endif
		</td>
		<td>{{ $m->realname }}</td>
		<td>{{ $m->email }}</td>
		<td>{{ $m->phone }}</td>
		<td>{{ $m->lasttime }}</td>
		<td>
			@if($m->status == 1)
			<span class="text-success">正常</span>
			@else
			<span class="text-danger">禁用</span>
			@endif
		</td>
		<td>
			@if(App::make('com')->ifCan('admin-edit'))
			<div data-url="{{ url('/console/admin/edit',$m->id) }}" data-title="修改" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('admin-pwd'))
			<div data-url="{{ url('/console/admin/pwd',$m->id) }}" data-title="修改密码" data-toggle='modal' data-target='#myModal' title="改密码" class="btn btn-xs btn-warning glyphicon glyphicon-eye-close btn_modal"></div>
			@endif
			@if($m->id != 1 && App::make('com')->ifCan('admin-del'))
			<a href="{{ url('/console/admin/del',$m->id) }}" class="btn btn-xs btn-danger glyphicon glyphicon-trash confirm"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->appends(['q'=>$key])->links() !!}
</div>
@endsection