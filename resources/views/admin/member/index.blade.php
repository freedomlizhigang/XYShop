@extends('admin.right')

@section('rmenu')
	@if(App::make('com')->ifCan('user-ranking'))
	<a href="{{ url('/console/user/ranking') }}" class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-sort-by-attributes-alt"></span> 消费排行</a>
	@endif
	@if(App::make('com')->ifCan('user-excel'))
	<a href="{{ url('/console/user/excel') }}" class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-share"></span> 导出用户</a>
	@endif

@endsection

@section('content')

<div class="clearfix">
	<form action="" class="form-inline" method="get">
		<input type="text" name="q" class="form-control" placeholder="请输入用户名、邮箱、电话查询..">
		<button class="btn btn-xs btn-info">搜索</button>
	</form>
</div>

<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="50">ID</th>
		<th width="80">会员等级</th>
		<th width="100">会员名</th>
		<th width="100">昵称</th>
		<th width="100">邮箱</th>
		<th width="100">电话</th>
		<th width="100">余额</th>
		<th>积分</th>
		<th>修改状态</th>
		<th>操作</th>
	</tr>
	@foreach($list as $m)
	<tr>
		<td>{{ $m->id }}</td>
		<td>{{ $m->groupname }}</td>
		<td>{{ $m->username }}</td>
		<td>{{ $m->nickname }}</td>
		<td>{{ $m->email }}</td>
		<td>{{ $m->phone }}</td>
		<td>{{ $m->user_money }} ￥</td>
		<td>{{ $m->points }}</td>
		<td>
			@if($m->status == 0)
			<span class="color_red">禁用</span> -> <a href="{{ url('/console/user/status',['id'=>$m->id,'status'=>1]) }}" class="text-success">正常</a>
			@else
			<span class="text-success">正常</span> -> <a href="{{ url('/console/user/status',['id'=>$m->id,'status'=>0]) }}" class="color_red">禁用</a>
			@endif
		</td>
		<td>
			@if(App::make('com')->ifCan('user-chong'))
			<div data-url="{{ url('/console/user/chong',$m->id) }}" data-title="充值" title="充值" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-success glyphicon glyphicon-credit-card btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('user-consumed'))
			<div data-url="{{ url('/console/user/consumed',$m->id) }}" data-title="消费" title="消费" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-warning glyphicon glyphicon-yen btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('user-edit'))
			<div data-url="{{ url('/console/user/edit',$m->id) }}" data-title="改密码" title="改密码" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-danger glyphicon glyphicon-eye-close btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('user-consume'))
			<a href="{{ url('/console/user/consume',$m->id) }}" class="btn btn-xs btn-info glyphicon glyphicon-stats" title="消费记录"></a>
			@endif
			@if(App::make('com')->ifCan('user-address'))
			<a href="{{ url('/console/user/address',$m->id) }}" class="btn btn-xs btn-primary glyphicon glyphicon-road" title="收货地址"></a>
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