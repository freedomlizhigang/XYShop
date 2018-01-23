@extends('admin.right')

@section('rmenu')
	@if(App::make('com')->ifCan('card-add'))
		<div data-url="{{ url('/console/card/add') }}" data-title="添加新卡" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加新卡</div>
	@endif
@endsection

@section('content')
<!-- 选出栏目 -->
<div class="clearfix">
	<form action="" class="form-inline pull-left form_excel" method="get">
		<select name="status" id="status" class="form-control mr10">
			<option value="">请选择状态</option>
			<option value="0">未激活</option>
			<option value="1">已激活</option>
		</select>
		开卡时间：<input type="text" name="starttime" class="form-control mr10" value="" id="laydate">
		到：<input type="text" name="endtime" class="form-control" value="" id="laydate2">
		<button class="btn btn-xs btn-info">查找</button>
		@if(App::make('com')->ifCan('card-excel'))
		<button class="btn btn-xs btn-primary btn_order">导出</button>
		@endif
	</form>

	<form action="" class="form-inline pull-right" method="get">
		<input type="text" name="q" class="form-control" placeholder="请输入手机号或者昵称..">
		<button class="btn btn-xs btn-info">搜索</button>
	</form>
</div>
<form action="" class="form-inline form_submit" method="get">
	{{ csrf_field() }}
	<table class="table table-striped table-hover mt10">
		<tr class="active">
			<th width="30"><input type="checkbox" class="checkall"></th>
			<th width="50">ID</th>
			<th width="100">卡号</th>
			<th width="50">密码</th>
			<th width="50">金额</th>
			<th width="200">会员</th>
			<th width="80">状态</th>
			<th width="160">激活时间</th>
			<th width="40">操作</th>
		</tr>
		@foreach($list as $a)
		<tr>
			<td><input type="checkbox" name="sids[]" class="check_s" value="{{ $a->id }}"></td>
			<td>{{ $a->id }}</td>
			<td>{{ $a->card_id }}</td>
			<td>{{ $a->card_pwd }}</td>
			<td>{{ $a->price }}</td>
			<td>@if(!is_null($a->user)){{ $a->user->username }} / {{ $a->user->nickname }}@endif</td>
			<td>
				@if($a->status == 0)
				<span class="text-warning">未激活</span>
				@else
				<span class="text-success">已激活</span>
				@endif
			</td>
			<td>{{ $a->init_time }}</td>
			<td>
				@if(App::make('com')->ifCan('card-edit'))
					<div data-url="{{ url('/console/card/edit',$a->id) }}" data-title="修改卡金额" title="修改卡金额" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info btn_modal glyphicon glyphicon-edit"></div>
				@endif
			</td>
		</tr>
		@endforeach
	</table>
</form>

<div class="pull-left" data-toggle="buttons">
	<div class="btn-group">
		<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
	</div>
	@if(App::make('com')->ifCan('card-del'))
	<span class="btn btn-danger btn-xs btn_del">批量删除</span>
	@endif
</div>

<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix pull-right">
{!! $list->appends(['status'=>$status,'starttime'=>$starttime,'endtime'=>$endtime])->links() !!}
</div>

<!-- 选中当前栏目 -->
<script>
	$(function(){
		$('.btn_order').click(function(){
			$('.form_excel').attr('action',"{{ url('/console/card/excel') }}").submit();
		});
		$('.btn_del').click(function(){
			if (!confirm("确实要删除吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/card/del') }}",'method':'post'}).submit();
			}
		});
		$(".checkall").bind('change',function(){
			if($(this).is(":checked"))
			{
				$(".check_s").each(function(s){
					$(".check_s").eq(s).prop("checked",true);
				});
			}
			else
			{
				$(".check_s").each(function(s){
					$(".check_s").eq(s).prop("checked",false);
				});
			}
		});
	});
	laydate({
        elem: '#laydate',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime:true,
        istoday: true, //是否显示今天
    });
    laydate({
        elem: '#laydate2',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
        istoday: true, //是否显示今天
    });
</script>
@endsection