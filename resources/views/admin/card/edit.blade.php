<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">金额：</td>
            <td>
                 <input type="number" min="0" name="price" value="{{ old('data.prices',$info->price) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>数字，多少钱</p>
            </td>
        </tr>
    </table>

	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/card/edit',$info->id) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>