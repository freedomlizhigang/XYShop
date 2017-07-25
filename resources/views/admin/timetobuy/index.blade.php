@extends('admin.right')

@if(App::make('com')->ifCan('timetobuy-add'))
@section('rmenu')
	<a href="{{ url('/console/timetobuy/add') }}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> 添加抢购</a>
@endsection
@endif

@section('content')
<!-- 选出栏目 -->
<div class="clearfix">
	<form action="" class="form-inline pull-left" method="get">
		<select name="status" id="status" class="form-control mr10">
			<option value="">请选择状态</option>
			<option value="1"@if($status == '1') selected="selected" @endif>进行中</option>
			<option value="0"@if($status == '0') selected="selected" @endif>关闭</option>
		</select>
		开始时间：<input type="text" name="starttime" class="form-control mr10" value="{{ $starttime }}" id="laydate">
		到：<input type="text" name="endtime" class="form-control" value="{{ $endtime }}" id="laydate2">
		<button class="btn btn-xs btn-info">查找</button>
	</form>

	<form action="" class="form-inline pull-right" method="get">
		<input type="text" name="q" class="form-control" value="{{ $key }}" placeholder="请输入标题关键字..">
		<button class="btn btn-xs btn-info">搜索</button>
	</form>
</div>
<form action="" class="form-inline form_submit" method="get">
{{ csrf_field() }}
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="30"><input type="checkbox" class="checkall"></th>
		<th width="80">排序</th>
		<th width="50">ID</th>
		<th>标题</th>
		<th width="80">抢购价</th>
		<th width="80">库存</th>
		<th width="80">状态</th>
		<th width="160">开始时间</th>
		<th width="160">结束时间</th>
		<th width="180">操作</th>
	</tr>
	@foreach($list as $a)
	<tr>
		<td><input type="checkbox" name="sids[]" class="check_s" value="{{ $a->id }}"></td>
		<td><input type="text" min="0" name="sort[{{$a->id}}]" value="{{ $a->sort }}" class="form-control input-xs"></td>
		<td>{{ $a->id }}</td>
		<td>
			{{ $a->title }}
		</td>
		<td><span class="text-success">{{ $a->price }} ￥</span></td>
		<td><span class="text-primary">{{ $a->good_num }}</span></td>
		<td>
			@if($a->status == 1)
			<span class="text-success">进行中</span>
			@else
			<span class="text-danger">关闭</span>
			@endif
		</td>
		<td>{{ $a->starttime }}</td>
		<td>{{ $a->endtime }}</td>
		<td>
			@if(App::make('com')->ifCan('timetobuy-edit'))
			<a href="{{ url('/console/timetobuy/edit',['id'=>$a->id]) }}" class="btn btn-xs btn-info glyphicon glyphicon-edit"></a>
			@endif
			@if(App::make('com')->ifCan('timetobuy-del'))
			<a href="{{ url('/console/timetobuy/del',$a->id) }}" class="confirm btn btn-xs btn-danger glyphicon glyphicon-trash"></a>
			@endif
		</td>
	</tr>
	@endforeach
</table>
</form>
<!-- 分页，appends是给分页添加参数 -->
<div class="pull-left" data-toggle="buttons">
	<div class="btn-group">
		<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
	</div>
	
	@if(App::make('com')->ifCan('timetobuy-sort'))
	<span class="btn btn-xs btn-warning btn_sort">排序</span>
	@endif

	@if(App::make('com')->ifCan('timetobuy-alldel'))
	<span class="btn btn-xs btn-danger btn_del">批量删除</span>
	@endif
</div>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
{!! $list->appends(['q'=>$key,'status'=>$status,'starttime'=>$starttime,'endtime'=>$endtime])->links() !!}
</div>
<!-- 选中当前栏目 -->
<script>
	$(function(){
		$('.btn_sort').click(function(){
			$('.form_submit').attr({'action':"{{ url('/console/timetobuy/sort') }}",'method':'post'}).submit();
		});
		$('.btn_del').click(function(){
			if (!confirm("确实要删除吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/timetobuy/alldel') }}",'method':'post'}).submit();
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
        istime: true,
    });
    laydate({
        elem: '#laydate2',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
</script>
@endsection