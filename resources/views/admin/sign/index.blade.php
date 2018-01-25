@extends('admin.right')

@section('content')
<form action="javascript:;" class="form-inline" id="form_ajax" method="post">
	{{ csrf_field() }}
	<table class="table table-striped">
		<tr>
			<td class="td_left">每日奖励：</td>
			<td>
				<input type="number" name="data[onepoint]" class="form-control input-xs" min="0" value="{{ $config->onepoint }}">
				<span class="color_red">*</span>
			</td>
		</tr>
		<tr>
			<td class="td_left">连续天数：</td>
			<td>
				<input type="number" name="data[days]" class="form-control input-xs" min="0" value="{{ $config->days }}">
				<span class="color_red">*</span>到达连续天数后有奖励
			</td>
		</tr>
		<tr>
			<td class="td_left">奖励：</td>
			<td>
				<input type="number" name="data[reward]" class="form-control input-xs" min="0" value="{{ $config->reward }}">
				<span class="color_red">*</span>到达连续天数后奖励多少积分
			</td>
		</tr>
		<tr>
			<td class="td_left"></td>
			<td>
				<div class="btn-group">
					<button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
					<div onclick='ajax_submit_form("form_ajax","{{ url('/console/sign/config') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
				</div>
			</td>
		</tr>
	</table>
	
</form>
@endsection