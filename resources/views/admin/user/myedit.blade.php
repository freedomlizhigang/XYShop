@extends('admin.right')

@section('content')
<form action="javascript:;" class="form-inline" id="form_ajax" method="post">
    {{ csrf_field() }}
    <table class="table table-striped">
        <tr>
            <td class="td_left">真实姓名：</td>
            <td>
                <input type="text" name="datas[realname]" class="form-control input-sm" value="{{ $info->realname }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        <tr>
            <td class="td_left">邮箱：</td>
            <td>
                <input type="text" name="datas[email]" class="form-control input-sm" value="{{ $info->email }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        <tr>
            <td class="td_left">电话：</td>
            <td>
                <input type="text" name="datas[phone]" class="form-control input-sm" value="{{ $info->phone }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left"></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/admin/myedit') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>
    </table>
</form>
@endsection