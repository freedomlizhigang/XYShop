<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">
        <tr>
            <td class="td_left">名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-sm" value="{{ $info->name }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">设备：</td>
            <td>
               <label class="radio-inline"><input type="radio" name="data[is_mobile]"@if($info->is_mobile != 1) checked="checked"@endif class="input-radio" value="0">电脑</label>
               <label class="radio-inline"><input type="radio" name="data[is_mobile]"@if($info->is_mobile == 1) checked="checked"@endif class="input-radio" value="1">手机</label>
            </td>
        </tr>
    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/adpos/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>