<form action="javascript:ajax_submit();" method="post" id="form_ajax">
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

    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/admin/pwd',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>