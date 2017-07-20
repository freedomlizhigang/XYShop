<form action="javascript:;" class="form-inline" id="form_ajax" method="post">
	{{ csrf_field() }}

	<table class="table table-striped">
		<tr>
            <td class="td_left">父分类：</td>
            <td>
                <select name="data[parentid]" id="parentid" class="form-control">
					<option value="0">选择分类</option>
					{!! $treeHtml !!}
				</select>
            </td>
        </tr>
        <tr>
            <td class="td_left">分类名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-sm" value="{{ $info->name }}">
                <p class="input-info"><span class="color_red">*</span>最多50字符</p>
            </td>
        </tr>
        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ $info->sort }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>数字越大越靠前</p>
            </td>
        </tr>
    </table>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/type/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>