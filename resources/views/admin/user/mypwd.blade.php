@extends('admin.right')

@section('content')
<form action="javascript:;" class="form-inline" id="form_ajax" method="post">
	{{ csrf_field() }}
    <table class="table table-striped">
        <tr>
            <td class="td_left">用户名：</td>
            <td>
                {{ $info->name }}
            </td>
        </tr>

        <tr>
            <td class="td_left">新密码：</td>
            <td>
                <input type="password" name="data[password]" class="form-control input-sm" value="{{ old('data.password') }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        <tr>
            <td class="td_left">确认密码：</td>
            <td>
                <input type="password" name="data[password_confirmation]" class="form-control input-sm" value="{{ old('data.password_confirmation') }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left"></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/admin/mypwd') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>
    </table>
</form>
@endsection