<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">会员组名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-sm" value="{{ old('data.name') }}">
                <p class="input-info"><span class="color_red">*</span>最多50字符</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">所需积分：</td>
            <td>
                <input type="text" name="data[points]" class="form-control input-sm" value="{{ old('data.points') }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">折扣：</td>
            <td>
                <input type="text" name="data[discount]" class="form-control input-sm" value="{{ old('data.discount') }}">
                <p class="input-info"><span class="color_red">*</span>单位 %</p>
            </td>
        </tr>
    </table>

	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/group/add') }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>