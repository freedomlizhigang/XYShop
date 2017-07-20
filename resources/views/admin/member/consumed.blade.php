<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">消费金额：</td>
            <td>
                <input type="number" name="data[user_money]" class="form-control input-sm" value="{{ old('data.user_money') }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">用户密码：</td>
            <td>
                <input type="password" name="pwd" class="form-control input-sm" value="{{ old('data.name') }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
    </table>
	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/user/consumed',['id'=>$id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>