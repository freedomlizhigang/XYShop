@extends('admin.right')

@section('rmenu')
@if(App::make('com')->ifCan('shop-add'))
	<a href="{{ url('/console/shop/add') }}" class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加商铺</a>
@endif

@endsection


@section('content')
<!-- 选出栏目 -->
<div class="clearfix">
	<form action="" class="form-inline pull-left" method="get">
		<select name="cate_id" id="catid" class="form-control">
			<option value="">请选择分类</option>
			{!! $shopcate !!}
		</select>
		<select name="status" id="status" class="form-control">
			<option value="">审核状态</option>
			<option value="1">已审核</option>
			<option value="0">未审核</option>
			<option value="-1">拒绝</option>
			<option value="-2">已停止</option>
		</select>
		<select name="active" id="active" class="form-control">
			<option value="">营业状态</option>
			<option value="1">正常</option>
			<option value="0">关闭</option>
		</select>
		<select name="ispos" id="ispos" class="form-control">
			<option value="">推荐状态</option>
			<option value="1">是</option>
			<option value="0">否</option>
		</select>
		<button class="btn btn-xs btn-info">查找</button>
	</form>

	<form action="" class="form-inline pull-right" method="get">
		<input type="text" name="q" class="form-control" placeholder="请输入商铺名..">
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
		<th width="60">用户名</th>
		<th>商铺名</th>
		<th width="100">分类</th>
		<th width="100">审核状态</th>
		<th width="100">营业状态</th>
		<th width="80">推荐状态</th>
		<th width="180">修改时间</th>
		<th width="280">操作</th>
	</tr>
	@foreach($list as $a)
		<tr>
			<td><input type="checkbox" name="sids[]" class="check_s" value="{{ $a->id }}"></td>
			<td><input type="text" min="0" name="sort[{{$a->id}}]" value="{{ $a->sort }}" class="form-control input-xs"></td>
			<td>{{ $a->id }}</td>
			<td>{{ $a->username }}</td>
			<td>{{ $a->shop_name }}</td>
			<td>
				{{ $a->shop_catid }}
			</td>
			<td>
			@if($a->shop_status == '1')
				<span class="text-success">审核通过</span>
			@endif
			@if($a->shop_status == '0')
				<span class="text-success">未审核</span>
			@endif
			@if($a->shop_status == '-1')
				<span class="text-success">拒绝</span>
			@endif
			@if($a->shop_status == '-2')
				<span class="text-success">已停止</span>
			@endif
			</td>
			<td>
				@if($a->active == 1)
				<span class="text-success">营业中</span>
				@else
				<span class="color-warning">休息中</span>
				@endif
			</td>
			<td>
				@if($a->ispos == 1)
				<span class="text-success">推荐</span>
				@else
				<span>普通</span>
				@endif
			</td>
			<td>{{ $a->updated_at }}</td>
			<td>
				
			</td>
		</tr>
	@endforeach
</table>
<!-- 添加进专题功能 -->
<div class="pull-left" data-toggle="buttons">
	<div class="btn-group">
		<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
	</div>
	
	@if(App::make('com')->ifCan('shop-sort'))
	<span class="btn btn-xs btn-warning btn_sort">排序</span>
	@endif

	@if(App::make('com')->ifCan('shop-alldel'))
	<span class="btn btn-xs btn-danger btn_del">批量删除</span>
	@endif
</div>
</form>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">
	{!! $list->appends(['q'=>$key])->links() !!}
</div>



<!-- 选中当前栏目 -->
<script>
	$(function(){
		$('.btn_del').click(function(){
			if (!confirm("确实要删除吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/good/alldel') }}",'method':'post'}).submit();
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
		$('#catid option[value=' + {{ $cate_id }} + ']').prop('selected','selected');
	})
</script>
@endsection