<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">姓名：</td>
            <td>
                <input type="text" name="data[people]" class="form-control input-sm" value="{{ $info->people }}">
                <p class="input-info"><span class="color_red">*</span>收货人姓名</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">电话：</td>
            <td>
                <input type="text" name="data[phone]" class="form-control input-sm" value="{{ $info->phone }}">
                <p class="input-info"><span class="color_red">*</span>收货人电话</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">地址：</td>
            <td>
                <input type="text" name="data[address]" class="form-control input-sm" value="{{ $info->address }}">
                <p class="input-info"><span class="color_red">*</span>6位及以上</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">默认：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[default]"@if ($info['default'] == 1) checked="checked"@endif class="input-radio" value="1">
                    是</label>
                <label class="radio-inline"><input type="radio" name="data[default]"@if ($info['default'] != 1) checked="checked"@endif class="input-radio" value="0">
                    否</label>
            </td>
        </tr>


    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/user/addressedit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>