@extends('admin.right')

@if(App::make('com')->ifCan('database-import'))
@section('rmenu')
<a href="{{ url('/console/database/import') }}" class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-log-in"></span> 数据库恢复</a>
@endsection
@endif

@section('content')
<form action="" method="post">
	{{ csrf_field() }}
	<table class="table table-striped table-hover">
		<tr class="active">
			<th width='50'>
				<input type="checkbox" class="checkall"></th>
			<th width="150">名称</th>
			<th width="120">类型</th>
			<th width="180">编码</th>
			<th>大小</th>
		</tr>
		@foreach($alltables as $a)
		<tr>
			<td>
				<input type="checkbox" name="tables[]" class="check_s" value="{{ $a->Name }}"></td>
			<td>{{ $a->Name }}</td>
			<td>{{ $a->Engine }}</td>
			<td>{{ $a->Collation }}</td>
			<td>{{ ($a->Data_length + $a->Index_length)/1048576 }} MB</td>
		</tr>
		@endforeach
	</table>
	<div class="btn-group" data-toggle="buttons">
		<div class="btn-group">
			<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
		</div>
		<button class="btn btn-xs btn-info">备份</button>
	</div>
</form>
<script>
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
</script>
@endsection