@extends('admin.right')


@section('rmenu')
	@if(App::make('com')->ifCan('goodcate-add'))
	<a href="{{ url('/console/goodcate/add/0') }}" class="btn btn-default btn-xs btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加商品分类</a>
	@endif
	@if(App::make('com')->ifCan('goodcate-cache'))
	<a href="{{ url('/console/goodcate/cache') }}" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-inbox"></span> 更新缓存</a>
	@endif
@endsection


@section('content')
<p class="alert alert-danger">理论上是无限级的分类，但是最好不要超过三级</p>
<form action="{{ url('/console/goodcate/sort') }}" class="form_submit" method="post">
	{{ csrf_field() }}
	<table class="table table-striped table-hover">
		<tr class="active">
			<th width="30"><input type="checkbox" class="checkall"></th>
			<td width="60">排序</td>
			<td width="60">ID</td>
			<td width="300">分类名称</td>
			<td width="100">手机端名称</td>
			<td width="100">首页显示</td>
			<td>菜单显示</td>
			<td width="100">操作</td>
		</tr>
		{!! $treeHtml !!}
	</table>
	<!-- 分页，appends是给分页添加参数 -->
	<div class="pull-left" data-toggle="buttons">
		<div class="btn-group">
			<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
		</div>
		@if(App::make('com')->ifCan('goodcate-sort'))
		<button type="submit" class="btn btn-xs btn-warning btn_sort">排序</button>
		@endif
	</div>
</form>
<script>
	$(function(){
		$('.btn_sort').click(function(){
			$('.form_submit').submit();
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
</script>
@endsection