@extends('admin.right')

@section('rmenu')
@if(App::make('com')->ifCan('extract-add'))
	<div data-url="{{ url('/console/extract/add') }}" data-title="添加自提点" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加自提点</div>
@endif

@endsection

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
		<th>区域</th>
		<th>地址</th>
		<th width="180">电话</th>
		<th width="80">状态</th>
		<th width="160">修改时间</th>
		<th width="180">操作</th>
	</tr>
	@foreach($list as $a)
	<tr>
		<td><input type="checkbox" name="sids[]" class="check_s" value="{{ $a->id }}"></td>
		<td><input type="text" min="0" name="sort[{{$a->id}}]" value="{{ $a->sort }}" class="form-control input-xs"></td>
		<td>{{ $a->id }}</td>
		<td>{{ $a->area }}</td>
		<td>{{ $a->address }}</td>
		<td>{{ $a->phone }}</td>
		<td>
			@if($a->status == 1)
			<span class="text-success">正常</span>
			@else
			<span class="text-danger">关闭</span>
			@endif
		</td>
		<td>{{ $a->updated_at }}</td>
		<td>
			@if(App::make('com')->ifCan('extract-edit'))
			<div data-url="{{ url('/console/extract/edit',['id'=>$a->id]) }}" data-title="修改自提点" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-info glyphicon glyphicon-edit btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('extract-del'))
			<a href="{{ url('/console/extract/del',$a->id) }}" class="confirm btn btn-xs btn-danger glyphicon glyphicon-trash"></a>
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
	
	@if(App::make('com')->ifCan('extract-sort'))
	<span class="btn btn-xs btn-warning btn_sort">排序</span>
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
			$('.form_submit').attr({'action':"{{ url('/console/extract/sort') }}",'method':'post'}).submit();
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