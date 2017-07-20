@extends('admin.alert')

@section('content')
<form action="" method="post">
	<input type="hidden" name="data[gids]" value="{{ $gids }}">
	{{ csrf_field() }}
	<table class="table table-striped table-hover">
		<tr class="active">
			<th width="50">ID</th>
			<th>标题</th>
			<th width="160">开始时间</th>
			<th width="160">结束时间</th>
		</tr>
		@foreach($list as $a)
		<tr>
			<td>{{ $a->id }}</td>
			<td>
				<label class="radio-inline">
					<input type="radio" name="data[hdid]" value="{{ $a->id }}" autocomplete="off">{{ $a->title }}</label>
			</td>
			<td>{{ $a->starttime }}</td>
			<td>{{ $a->endtime }}</td>
		</tr>
		@endforeach
	</table>
	<div class="btn-group mt10">
		<button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
		<button type="submit" name="dosubmit" class="btn btn-xs btn-info">提交</button>
	</div>
</form>
<!-- 分页，appends是给分页添加参数 -->
<div class="pages clearfix">{!! $list->links() !!}</div>
@endsection