@extends('admin.right')

@section('content')
<form action="javascript:;" class="form-inline" id="form_ajax" method="post">
	{{ csrf_field() }}
	<table class="table table-striped">
		<tr>
			<td class="td_left">站点名称：</td>
			<td>
				<input type="text" name="data[sitename]" class="form-control input-md" value="{{ $config->sitename }}">
				<span class="color_red">*</span>
			</td>
		</tr>
		<tr>
			<td class="td_left">SEO标题：</td>
			<td>
				<input type="text" name="data[title]" class="form-control input-md" value="{{ $config->title }}">
				<span class="color_red">*</span>
			</td>
		</tr>
		<tr>
			<td class="td_left">关键字：</td>
			<td>
				<input type="text" name="data[keyword]" class="form-control input-md" value="{{ $config->keyword }}">
			</td>
		</tr>
		<tr>
			<td class="td_left">描述：</td>
			<td>
				<textarea name="data[describe]" class="form-control input-lg" rows="5">{{ $config->describe }}</textarea>
			</td>
		</tr>
		<tr>
			<td class="td_left">主题：</td>
			<td>
				<input type="text" name="data[theme]" class="form-control input-md" value="{{ $config->theme }}">
				<span class="color_red">*</span>
			</td>
		</tr>
		<tr>
			<td class="td_left">联系人：</td>
			<td>
				<input type="text" name="data[person]" class="form-control input-md" value="{{ $config->person }}">
			</td>
		</tr>
		<tr>
			<td class="td_left">电话：</td>
			<td>
				<input type="text" name="data[phone]" class="form-control input-md" value="{{ $config->phone }}">
			</td>
		</tr>
		<tr>
			<td class="td_left">邮箱：</td>
			<td>
				<input type="text" name="data[email]" class="form-control input-md" value="{{ $config->email }}">
			</td>
		</tr>
		<tr>
			<td class="td_left">地址：</td>
			<td>
				<textarea name="data[address]" class="form-control input-lg" rows="5">{{ $config->address }}</textarea>
			</td>
		</tr>
		<tr>
			<td class="td_left">介绍：</td>
			<td>
				<textarea name="data[content]" class="form-control input-lg" rows="5">{{ $config->content }}</textarea> 
			</td>
		</tr>
		<tr>
			<td class="td_left"></td>
			<td>
				<div class="btn-group">
					<button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
					<div onclick='ajax_submit_form("form_ajax","{{ url('/console/config/index') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
				</div>
			</td>
		</tr>
	</table>
	
</form>
@endsection