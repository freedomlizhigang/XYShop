@extends('admin.right')


@section('rmenu')
	@if(App::make('com')->ifCan('shopcate-add'))
	<div data-url="{{ url('/console/shopcate/add/0') }}" data-title="添加分类" data-toggle='modal' data-target='#myModal' class="btn btn-default btn-xs btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加分类</div>
	@endif
	@if(App::make('com')->ifCan('shopcate-cache'))
	<a href="{{ url('/console/shopcate/cache') }}" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-inbox"></span> 更新缓存</a>
	@endif
@endsection


@section('content')
<form action="{{ url('/console/shopcate/sort') }}" class="form_submit" method="post">
{{ csrf_field() }}
<table class="table table-striped table-hover">
	<tr class="active">
		<th width="30"><input type="checkbox" class="checkall"></th>
		<td width="60">排序</td>
		<td width="60">ID</td>
		<td width="300">分类名称</td>
		<td width="120">手机名称</td>
		<td width="120">是否显示</td>
		<td>操作</td>
	</tr>
	{!! $treeHtml !!}
</table>
<!-- 分页，appends是给分页添加参数 -->
<div class="pull-left" data-toggle="buttons">
	<div class="btn-group">
		<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
	</div>
	@if(App::make('com')->ifCan('shopcate-sort'))
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