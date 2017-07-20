<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">密码：</td>
            <td>
                <input type="password" name="data[password]" class="form-control input-sm" value="">
                <p class="input-info"><span class="color_red">*</span>6位及以上</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">重复密码：</td>
            <td>
                <input type="password" name="data[repassword]" class="form-control input-sm" value="">
                <p class="input-info"><span class="color_red">*</span>6位及以上</p>
            </td>
        </tr>
    </table>

	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/user/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>