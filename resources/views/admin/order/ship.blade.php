<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">
		<input type="hidden" name="data[order_id]" value="{{ $id }}">
        <tr>
            <td class="td_left">商家备注：</td>
            <td>
                <textarea name="data[shopmark]" class="form-control" placeholder="商家备注" rows="5"></textarea>
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>
    </table>
	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/order/ship',['id'=>$id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>