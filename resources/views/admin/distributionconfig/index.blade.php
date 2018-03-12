@extends('admin.right')

@section('content')
<form action="javascript:;" class="form-inline" id="form_ajax" method="post">
	{{ csrf_field() }}
	<table class="table table-striped">
		<tr>
			<td class="td_left">一级返现比例：</td>
			<td>
				<input type="number" name="data[son_proportion]" class="form-control input-xs" min="0" value="{{ $info->son_proportion }}">
				<span class="color_red">*</span> 直接来的用户消费返现比例，按百分比：如 1 为 1%
			</td>
		</tr>
		<tr>
			<td class="td_left">二级返现比例：</td>
			<td>
				<input type="number" name="data[sun_proportion]" class="form-control input-xs" min="0" value="{{ $info->sun_proportion }}">
				<span class="color_red">*</span> 间接来的用户消费返现比例，按百分比：如 1 为 1%
			</td>
		</tr>
		<tr>
			<td class="td_left">是否开启：</td>
			<td>
				<label class="radio-inline"><input type="radio" name="data[unlock]"@if ($info['unlock'] == 1) checked="checked"@endif class="input-radio" value="1">
					启用</label>
				<label class="radio-inline"><input type="radio" name="data[unlock]"@if ($info['unlock'] != 1) checked="checked"@endif class="input-radio" value="0">
					禁用</label>
			</td>
		</tr>
		<tr>
			<td class="td_left"></td>
			<td>
				<div class="btn-group">
					<button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
					<div onclick='ajax_submit_form("form_ajax","{{ url('/console/distributionconfig/index') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
				</div>
			</td>
		</tr>
	</table>
	
</form>
@endsection