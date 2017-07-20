<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">

        <tr>
            <td class="td_left">处理意见：</td>
            <td>
                <textarea name="data[shopmark]" placeholder="处理意见" class="form-control" rows="4"></textarea>
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">状态：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[status]" checked="checked" class="input-radio" value="1">
                    退货</label>
                <label class="radio-inline"><input type="radio" name="data[status]" class="input-radio" value="2">不退货</label>
            </td>
        </tr>

    </table>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/returngood/status',['id'=>$id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>